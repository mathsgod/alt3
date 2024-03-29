<?php
// Created By: Raymond Chong
// Created Date: 23/2/2010
// Last Updated:
use App\ACL;

class ACL_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);
        $rt->order("user_id", "desc");

        $rt->addDel();
        $rt->add("Module", "module")->ss();
        $rt->Add("Path", "path")->ss();
        $rt->Add("Action", "action")->ss();
        $rt->Add("User", "user_id");
        $rt->Add("UserGroup", "usergroup_id");
        $rt->Add("Special User", "special_user");
        $rt->Add("Value", "value")->sortable();
        $rt->add("Type", "type")->sortable();

        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->add("path", "path()");
        $rt->add("user_id", "User()")->alink("v");
        $rt->add("type", "Type()");
        $rt->add("usergroup_id", "UserGroup()")->alink("v");
        $rt->add("special_user", "SpecialUser()");
        $rt->source = ACL::Query();
        return $rt;
    }
}
