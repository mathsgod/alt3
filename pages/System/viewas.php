<?php
class System_viewas extends ALT\Page
{
    public function get($user_id, $ref)
    {
        if ($user_id) {
            $_SESSION["app"]["org_user_id"] = $this->app->user->user_id;
            $_SESSION["app"]["user_id"]  = $user_id;

            if ($ref) {
                header("location: $ref");
            } else {
                $this->redirect("Dashboard");
            }
            return;
        }


        $ref = $_SERVER['HTTP_REFERER'];
        $t = $this->createT(App\User::Query()->toArray());
        $t->add("Username", "username");
        $t->add("User group", function ($u) {

            $ugs = [];
            foreach ($u->UserGroup() as $ug) {
                $ugs[] = (string) $ug;
            }
            return implode("<br/>", $ugs);
        });

        $t->add("view as")->a()->text("View as")->attr("href", function () use ($ref) {
            $o = p($this)->data("object");
            return "System/viewas?" . http_build_query(["user_id" => $o->user_id, "ref" => $ref]);
        });
        $this->write($t);
    }
}
