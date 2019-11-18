<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
class User_2step extends ALT\Page
{
    public function barcode($data)
    {
        $this->write(file_get_contents("https://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=" . $data));
    }

    public function post($cmd)
    {
        switch ($cmd) {
            case "remove":
                $u = $this->app->user;
                $u->secret = "";
                $u->save();
                $this->alert->info("2-step verification removed");
                $this->redirect();
                break;
            default:
                $this->redirect("User/2step?auto_create=1");
        }
    }

    private function create_code()
    {
        require_once(SYSTEM . "/plugins/GoogleAuthenticator/GoogleAuthenticator.php");
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $u = $this->app->user;
        $u->secret = $secret;
        $u->save();

        $b = $this->createBox();
        $b->header->title = "2-step secret key";

        $username = $this->app->user->username;
        $hostname = $_SERVER["HTTP_HOST"];

        $b->body->innerHTML .= "Your secret key are created: <b>$secret</b><br/>";
        $b->body->innerHTML .= "Host: $hostname <br/>";

        $data = urlencode(sprintf("otpauth://totp/%s@%s?secret=%s", $username, $hostname, $secret));
        $b->body->innerHTML .= "<div align='center'><img src='User/2step/barcode?data={$data}' /></div>";

        $this->write($b);
    }

    public function get($auto_create)
    {
        // $this->header()->setTitle("2-step verfication");
        if ($auto_create) {
            $this->create_code();
            return;
        }

        if ($this->app->secret) {
            $f = $this->createForm("2-step verification already set, remove it?");
            $f->action("User/2step?cmd=remove");
            $this->write($f);
        } else {
            $f = $this->createForm("Click submit to create 2-step verification");
            $f->action("");
            $this->write($f);
        }
    }
}
