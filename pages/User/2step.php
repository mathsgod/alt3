<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
use Google\Authenticator\GoogleAuthenticator;

class User_2step extends ALT\Page
{
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
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $u = $this->app->user;
        $u->secret = $secret;
        $u->save();

        $b = $this->createCard();
        $b->setAttribute("info");
        $b->setAttribute("outline");
        $b->header->title = "2-step secret key";

        $username = $this->app->user->username;
        $hostname = $_SERVER["HTTP_HOST"];

        $b->body->innerHTML .= "Your secret key are created: <b>$secret</b><br/>";
        $b->body->innerHTML .= "Host: $hostname <br/>";

        $content = file_get_contents($g->getUrl($username, $hostname, $secret));
        $content = base64_encode($content);
        $img = p("img")->attr("src", "data:image/png;base64," . $content);
        $b->body->innerHTML .= "<div align='center'>" . $img . "</div>";

        $this->write($b);
    }

    public function get($auto_create)
    {
        $this->header->title = "2-step verfication";

        if ($auto_create) {
            $this->create_code();
            return;
        }

        if ($this->app->user->secret) {
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
