<?php
class System_login extends R\Page
{
    public function post()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $code = $_POST["code"];

        try {
            $this->app->login($username, $password, $code);
        } catch (Exception $e) {
            return ["code" => 400, "error" => ["message" => $e->getMessage()]];
        }

        return ["code" => 200];
    }

    public function get()
    {
        // redirect to dashboard
        $this->_redirect("");
    }

    public function forget_password()
    {

        $username = $_POST["username"];
        $email = $_POST["email"];

        if (!$username) {
            return ["error" => ["message" => "Username cannot be null"]];
        }

        if (!$email) {
            return ["error" => ["message" => "Email cannot be null"]];
        }

        $w[] = ["username=?", $username];
        $w[] = ["email=?", $email];
        if ($user = \App\User::first($w)) {
            try {
                $user->sendPassword();
            } catch (Exception $e) {
                return ["error" => ["message" => $e->getMessage()]];
            }
        }
    }
}
