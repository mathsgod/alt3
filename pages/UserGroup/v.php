<?php
// Created By: Raymond Chong
// Last Updated:
class UserGroup_v extends ALT\Page
{
    public function get()
    {

        $obj = $this->object();
        if ($obj->usergroup_id != 1) {
            // $this->navbar()->addButton("Add User", $obj->uri("userlist"));
        }

        $mv = $this->createV();
        $mv->add("Name", "name");
        $mv->add("Inactive", "inactive")->format('tick');
        $mv->add("Remark", "remark");
        $this->write($mv);

        $mt = $this->createT($obj->UserList());

        $c = $mt->addDel();
        $c->label = "Detach from group";
        $mt->add("Username", "User()->username");
        $mt->add("First name", "User()->first_name");
        $mt->add("Last name", "User()->last_name");
        $this->write($mt);
    }
}