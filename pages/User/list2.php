<?php
use App\User;

class User_list2 extends ALT\Page
{
    public function get()
    {
        $rt = $this->createDT([$this, "ds"]);
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();

        $rt->order("username", "desc");

        $rt->add("Username", "username")->ss();

        $rt->add("User group", "usergroup_id")->searchOption(App\UserGroup::find());

        $rt->add("First name", "first_name")->ss();//->ss();
        $rt->add("Last name", "last_name")->ss();//->ss();
        $rt->add("Phone", "phone")->ss();//->ss()->editable();
        $rt->add("Email", "email")->ss();//->ss();//->overflow("hidden");
        $rt->add("Status", "status")->sort()->searchSelect2(User::$_Status);
        $rt->add("Expiry date", "expiry_date")->sort()->searchDate();
        $rt->add("Join date", "join_date")->sort()->searchDate();//->editable("date");
        $rt->add("Language", "language");//->nowrap()->sort();
        $rt->add("Skin", "skin");//->nowrap()->sort();
        $rt->add("Online", "is_online");

//        $rt->subHTML([$this, "test"]);
        $this->write($rt);
    }

    public function ds()
    {
        $r = $this->createDTResponse(App\User::Query());
        $r->fields = ["username", "first_name", "last_name", "phone", "email", "epxiry_date", "join_date", "language", "skin"];
        $r->addView();
        $r->addEdit();
        $r->addDel();
        $r->add("usergroup_id", function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=?)", $v];
        });

        $r->add("status", "Status()");

        $r->add("is_online","isOnline()")->format('tick');


        return $r;
    }

}