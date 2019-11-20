<?php

class System_htpasswd extends ALT\Page
{
    public function post()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $encrypted_password = crypt($password, base64_encode($password));


        $this->alert->info($username . ':' . $encrypted_password);
        $this->redirect();
    }

    public function get()
    {
        $e = $this->createE(null);
        $e->add("Username")->input("username");
        $e->add("Password")->input("password");
        $this->write($this->createForm($e));
    }
}
