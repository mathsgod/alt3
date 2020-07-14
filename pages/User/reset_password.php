<?php
class User_reset_password extends ALT\Page
{
    public function post()
    {
        if (!$this->app->user->isPowerUser() && !$this->app->user->isAdmin()) {
            return $this->app->accessDeny($this->request);
        }

        $u = $this->object();
        $u->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $u->save();
        $this->alert->info("Password updated");
        $this->redirect();
    }

    public function get()
    {
        $config = $this->app->config;
        $obj = $this->object();

        if (!$this->app->user->isPowerUser() && !$this->app->user->isAdmin()) {
            return $this->app->accessDeny($this->request);
        }
    }
}
