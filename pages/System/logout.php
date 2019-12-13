<?php

use App\UserLog;

class System_logout extends R\Page
{
    public function get()
    {
        
        if ($this->app->logined()) {
            if ($_SESSION["app"]["org_user_id"]) {
                $this->app->user = new App\User($_SESSION["app"]["org_user_id"]);
                unset($_SESSION["app"]["org_user_id"]);
            }

            $this->app->user->offline();

            $user_id = $this->app->user->user_id;
            $w[] = "user_id=$user_id";
            if ($ul = UserLog::first($w, "userlog_id desc")) {
                $ul->logout_dt = date("Y-m-d H:i:s");
                $ul->save();
            }
        }

        $_SESSION["app"] = [];

        $pi = $this->app->pathinfo();
        header("location: " . $pi["cms_base"]);
    }
}
