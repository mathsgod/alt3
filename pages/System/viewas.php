<?

class System_viewas extends ALT\Page
{
    public function get($user_id, $ref)
    {
        if ($user_id) {
            $_SESSION["app"]["user"] = new App\User($user_id);
            $_SESSION["app"]["org_user"] = $this->app->user;

            if ($ref) {
                header("location: $ref");
            } else {
                $this->redirect("Dashboard");
            }
            return;
        }


        $ref = $_SERVER['HTTP_REFERER'];
        $t = $this->createT(App\User::Find());
        $t->add("Username", "username");
        $t->add("")->button()->href(function ($o) use ($ref) {
            return "System/viewas?" . http_build_query(["user_id" => $o->user_id, "ref" => $ref]);
        })->text("View as");
        $this->write($t);
    }
}