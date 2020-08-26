<?php

class User_remove_2step extends App\Page
{
    public function get()
    {
        if ($this->app->user->isAdmin()) {
            $obj = $this->object();
            $obj->secret = "";
            $obj->save();
            $this->alert->info("2-step removed");
            $this->redirect();
        }
    }
}
