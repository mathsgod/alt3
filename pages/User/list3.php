<?php
use App\User;

class User_list3 extends ALT\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->cellUrl = "User";

        $rt->addView();
        $rt->addEdit();
        $rt->addDel();

        $rt->order("username", "desc");

        $rt->add("Username", "username")->ss();

        $rt->add("User group", "usergroup_id")->searchMultiple(App\UserGroup::find());
//        $rt->add("User group", "usergroup_id")->searchOption(App\UserGroup::find());

        $rt->add("First name", "first_name")->ss();//->ss();
        $rt->add("Last name", "last_name")->ss();//->ss();
        $rt->add("Phone", "phone")->ss()->editable();
        $rt->add("Email", "email")->ss();//->ss();//->overflow("hidden");
        $rt->add("Status", "status")->sort()->searchSelect2(User::$_Status)->editable("select", ["Active", "Inacive"]);
        $rt->add("Expiry date", "expiry_date")->sort()->searchDate();
        $rt->add("Join date", "join_date")->sort()->searchDate();//->editable("date");
        $rt->add("Language", "language");//->nowrap()->sort();
        $rt->add("Skin", "skin");//->nowrap()->sort();
        $rt->add("Online", "is_online");

//        $rt->subHTML([$this, "test"]);
        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = App\User::Query();

        $rt->key("user_id");

        $rt->fields = ["username", "first_name", "last_name", "email", "epxiry_date", "join_date", "language", "skin"];

        $rt->add("phone", "phone");
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();
        $rt->add("usergroup_id", function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchCallback(function ($v) {
            $s = "'" . implode("','", $v) . "'";
            return "user_id in (select user_id from UserList where usergroup_id in ($s))";
        });

        $rt->add("status", function ($o) {
            return ["value" => $o->status, "content" => $o->Status()];
        });

        $rt->add("is_online", "isOnline()")->format('tick');

        return $rt;
    }

}