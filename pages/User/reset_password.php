<?php
class User_reset_password extends ALT\Page
{
    public function post()
    {
        if ($this->app->user->isAdmin()) {
            $u = $this->object();
        } else {
            $u = $this->app->user;
        }
        $u->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $u->save();
        $this->alert->info("Password updated");
        $this->redirect();
    }

    public function get()
    {
        $config = $this->app->config;
        $obj = $this->object();

        $obj->password = "";
        $mv = $this->createE($obj);
        $mv->add("New password")->input("password")->type("password")->required()->attr("id", "password")
            ->minLength($config["user"]["password-length"]);
        $mv->add("Retype password")->input("password2")->type("password")->required();
        $f = $this->createForm($mv);
        $f->action();
        $this->write($f);
    }
}
