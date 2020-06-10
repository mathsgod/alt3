<?php

class _cancel_viewas extends App\Page
{
    public function get()
    {
        if ($_SESSION["app"]["org_user_id"]) {
            $_SESSION["app"]["user_id"] = $_SESSION["app"]["org_user_id"];
            unset($_SESSION["app"]["org_user_id"]);
        }
        $this->redirect();
    }
}
