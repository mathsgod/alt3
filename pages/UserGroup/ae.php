<?php

class UserGroup_ae extends ALT\Page
{
    public function get()
    {
        $mv = $this->createE();
        $mv->add("Name")->input("name")->required();
        $mv->add("Code")->input("code");
        $mv->add("Remark")->textarea("remark");

        $this->write($this->createForm($mv));
    }
}