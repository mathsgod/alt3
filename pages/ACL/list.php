<?php
// Created By: Raymond Chong
// Created Date: 23/2/2010
// Last Updated:
use App\ACL;

class ACL_list extends App\Page
{
    public function get()
    {
        $jv = $this->createRT2([$this, "ds"]);
        $jv->order("user_id", "desc");

        $jv->addDel();
        $jv->add("Module", "module")->ss();
        $jv->Add("Path", "Path()")->ss();
        $jv->Add("Action", "action")->ss();
        $jv->Add("User", "user_id");
        $jv->Add("UserGroup", "UserGroup()")->sort("usergroup_id")->ALink("v");
        $jv->Add("Special User", "SpecialUser()")->sort("special_user");

        $jv->Add("Value", "value")->sort(); //->align("center");

        $jv->add("Type", "type")->sort();
        // $jv->Add("Code", "code")->Format("tick");
        $this->write($jv);
    }

    public function ds($rt)
    {
        $rt->add("path", "Path()");
        $rt->add("user_id", "User()")->alink("v");
        $rt->add("type", "Type()");
        $rt->source = ACL::Query();
        return $rt;
    }
}
