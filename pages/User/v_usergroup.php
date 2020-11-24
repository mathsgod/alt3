<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated: 2018-08-21
use App\UserList;

class User_v_usergroup extends App\Page
{
    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);
        $rt->addView();
        $rt->add("UserGroup", "usergroup");
        $this->write($rt);
    }

    public function ds($rt)
    {
        $q = App\UserGroup::Query()->leftJoin('UserList', 'UserList.usergroup_id=UserGroup.usergroup_id');
        $q->where("UserList.user_id=" . $this->object()->user_id);
        $rt->source = $q;
        $rt->add("usergroup", "__toString()");
        return $rt;
    }
}
