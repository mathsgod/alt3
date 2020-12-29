<?php

/**
 * Created by: Raymond Chong
 * Date: 2020-10-15 
 */
class User_test2 extends ALT\Page
{
    public function get()
    {

        $btn = html("a")->value(1)->onclick("SelectAll(this)")->href("abc")->class("btn btn-info")->text("Select/Diselect All");
        $this->write($btn);
    }
}
