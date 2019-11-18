<?php
use R\WebAuthn;

class User_profile extends ALT\Page
{
    public function post()
    {
        if ($_FILES && $_FILES["file"]["error"] == 0) {
            $u = $this->app->user;
            mkdir(getcwd() . "/data/" . $u->username);
            move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/data/" . $u->username . "/profile.image");
        } else {
            $u = $this->app->user;
            $u->bind($_POST);
            $u->save();
            App::Msg("User information updated");
        }
        App::Redirect("User/profile");
    }

    public function postFido2()
    {
        $weba = new WebAuthn($_SERVER['HTTP_HOST']);
        $user = $this->app->user;
        $user->credential = json_encode($weba->register($_POST));
        $user->save();
        return ["code" => 200, "username" => $user->username];
    }

    public function getCredential()
    {
        $weba = new WebAuthn($_SERVER['HTTP_HOST']);
        return ["data" => $weba->prepare_challenge_for_registration($this->app->user->username, $this->app->user->user_id . "")];
    }

    public function get()
    {
        $this->app->savePlace();
        $this->header("User profile");
        $user = $this->app->user;
        $tpl = $this;
        $tpl->assign("user_id", $user->user_id);
        $tpl->assign("name", $user);
        $tpl->assign("usergroup", \App::User()->UserGroup()->implode(","));

        $desc = new App\BS\Description($this);
        $desc->classList->remove("dl-horizontal");
        $desc->add("Username", $user->username);
        $desc->add("First name", $user->first_name);
        $desc->add("Last name", $user->last_name);
        $desc->add("Email", $user->email);
        $desc->add("Phone", $user->phone);

        $desc->add("Status", $user->Status());

        $tpl->assign("description", $desc);

        $tpl->assign("user_update_box", $this->getUpdateBox());
        $tpl->assign("userlog", $this->getUserLogBox());
        $tpl->assign("eventlog", $this->getUserActionBox());
    }

    public function getUpdateBox()
    {
        $o = $this->createE($this->app->user);

        $o->add("First name")->input("first_name")->required();
        $o->add("Last name")->input("last_name");
        $o->add("Phone")->input("phone");
        $o->add("Email")->email("email")->required();
        $f = $this->createForm($o);
        $f->show_back = false;
        return $f;
    }

    public function getUserLogBox()
    {
        $mt = $this->createT($this->app->user->UserLog(null, "userlog_id desc", 10));
        $mt->add("Login time", "login_dt");
        $mt->add("Logout time", "logout_dt");
        $mt->add("IP address", "ip");
        $mt->add("Result", "result");

        $mt->header("Userlog last 10 record");

        return $mt;
    }

    public function getUserActionBox()
    {
        $mt = $this->createT($this->app->user->EventLog(null, "eventlog_id desc", 10));
        $mt->add("ID", "eventlog_id");
        $mt->add("Class", "class");
        $mt->add("Object ID", "id");
        $mt->add("Action", "action");
        $mt->add("Created time", "created_time");
        $mt->header("Eventlog last 10 record");
        return $mt;
    }
}
