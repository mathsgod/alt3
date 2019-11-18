<?php

class User_layout extends App\Page {
    public function get($name, $value) {
        $u = App::User();
        $setting = json_decode($u->setting, true);
        $setting[$name] = $value;
        $u->setting = json_encode($setting);
        $u->save();
    }
}

?>