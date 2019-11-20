<?php

class System_remove extends App\Page {
    public function get() {
        $path = getcwd() . "/system/source/bower/bower";

        `rm -rf $path`;
    }
}

?>