<?php

class User_setting extends App\Page
{
    public function get($language, $layout)
    {
        if ($layout) {
            $u = $this->app->user;
            $setting = json_decode($u->setting, true);
            $setting["layout"] = $layout;
            $u->setting = json_encode($setting);
            $u->save();
            header("location: " . $_SERVER["HTTP_REFERER"]);
            return;
        }

        $u = My::User();
        $u->language = $language;
        $u->save();

        header("location: " . $_SERVER["HTTP_REFERER"]);
    }
}

?>