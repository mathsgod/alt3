<?php

use App\UserGroup;

class UserGroup_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();

        $rt->addSubRow("subrow1");

        $rt->add("Usergroup ID", "usergroup_id")->sort()->searchEq();
        $rt->add("Name", "name")->ss();
        $rt->add("Code", "code")->ss();
        $rt->add("User count", "usercount");

        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = UserGroup::Query();
        $rt->addSubRow("subrow1", [$this, "user"], "usergroup_id");
        $rt->add("usercount", function ($o) {
            return $o->UserList->count();
        });
        return $rt;
    }

    public function user($usergroup_id)
    {
        $ug = new UserGroup($usergroup_id);
        $t = $this->createTable($ug->User());

        $t->addView();
        $t->addEdit();
        $t->add("Username", "username");
        $t->add("First name", "first_name");
        $t->add("Last name", "last_name");
        $t->add("Email", "email");
        $this->write($t);
    }
}
