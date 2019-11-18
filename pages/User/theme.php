<?php

class User_theme extends App\Page {
    public function get($bs_theme) {
        $u = App::User();
        if ($bs_theme == "default") {
            $u->bs_theme = '';
        } else {
            $u->bs_theme = $bs_theme;
        }

        $u->save();
        App::Redirect();
    }
}

?>