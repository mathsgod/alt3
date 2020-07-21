<?php

//Created by Raymond Chong
//Date: 2020-06-26
class DynamicPage_ae extends ALT\Page
{
    public function get()
    {
        $e = $this->createE();
        $e->add("Name")->input("name")->required();
        $e->add("Path")->input("path")->required();

        $this->write($this->createForm($e));
    }
}
