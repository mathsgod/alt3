<?php

class User_setting extends App\Page
{
    public function get($language)
    {
        $this->app->user->language = $language;
        $this->app->user->save();
        header("location: " . $_SERVER["HTTP_REFERER"]);
    }
}
