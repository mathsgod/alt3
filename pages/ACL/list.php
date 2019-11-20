<?php
// Created By: Raymond Chong
// Created Date: 23/2/2010
// Last Updated:
use App\ACL;

class ACL_list extends App\Page
{
    public function get()
    {
        $jv = $this->createRT([$this, "ds"]);
        $jv->order("user_id", "desc");

        $jv->addDel();
        $jv->add("Module", "module")->search()->sort();
        $jv->Add("Path", "Path()")->index("path")->search()->sort();
        $jv->Add("Action", "action")->search()->sort();
        $jv->Add("User", "User()")->sort("user_id")->ALink("v");
        $jv->Add("UserGroup", "UserGroup()")->sort("usergroup_id")->ALink("v");
        $jv->Add("Special User", "SpecialUser()")->sort("special_user");

        $jv->Add("Value", "value")->sort();//->align("center");

        $jv->add("Type", "Type()")->index("type")->sort();
        // $jv->Add("Code", "code")->Format("tick");
        $this->write($jv);
    }

    public function ds($jv)
    {
        $w = $jv->where();
        return array("total" => ACL::count($w),
            "data" => ACL::find($w, $jv->Order(), $jv->Limit()));
    }
}
