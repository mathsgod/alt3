<?php

use App\User;

class User_list extends ALT\Page
{
    public function get()
    {
        // outp(App\User::find());


        $rt = $this->createRT2([$this, "ds"]);

        //$rt->selectable = true;
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();

        $rt->add("Username", "username")->ss();
        $rt->add("User group", "usergroup_id")->searchOption(App\UserGroup::find());
        /*function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchOption(App\UserGroup::find(), null, "usergroup_id")->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=?)", $v];
        });*/
        $rt->add("First name", "first_name")->ss();
        $rt->add("Last name", "last_name")->ss();
        $rt->add("Phone", "phone")->ss()->editable();
        $rt->add("Email", "email")->ss(); //->overflow("hidden");
        $rt->add("Status", "status")->sort()->searchOption(User::STATUS);
        //        $rt->add("Status", "Status()")->index("status")->sort()->searchOption(User::$_Status);
        // $rt->add("Expiry date", "expiry_date")->sort()->searchDateRange();
        $rt->add("Expiry date", "expiry_date")->sort()->searchDate();
        $rt->add("Join date", "join_date")->sort()->searchDate();
        $rt->add("Language", "language")->sort();
        $rt->add("Skin", "skin")->sort(); //->noHide();

        $rt->add("Online", "isonline");
        //$rt->addButton("test")->attr("onClick","window.self.location='User/1/v'");
        // $rt->add("Style","style")->attr("data-format",'json')->attr("collapsed",true);

        /*        $rt->exports[] = "csv";
        $rt->exports[] = "xlsx";
        $rt->buttons[] = ["text" => "Hello", "action" => "onClickHello"];
         */

        //$rt->selectable = true;
        $rt->cellUrl = "User";
        $this->write($rt);
    }

    public function ds($rt, $t)
    {
        $rt->columns = [
            "status" => "Status()",
            "isonline" => [
                "content" => "isOnline()",
                "format" => "tick"
            ], "username" => [
                "content" => "username",
                "alink" => "v"
            ]
        ];


        $rt->key("user_id");
        $rt->add("usergroup_id", function ($obj) {
            return $obj->UserGroup()->implode(",");
        })->searchCallback(function ($v) {
            return ["user_id in (select user_id from UserList where usergroup_id=:usergroup_id)", ["usergroup_id" => $v]];
        });
        //$rt->add("isonline", "isOnline()")->format("tick");
        //$rt->add("username", "username")->alink("v");

        if ($t >= 0) $w[] = ["status=:status", ["status" => $t]];

        $rt->source = App\User::Query()->where($w);


        return $rt;
    }

    public function ds1($rt)
    {
        $w = $rt->where();

        if ($_GET["t"] >= 0) $w[] = ["status=?", $_GET["t"]];
        return [
            "total" => User::count($w),
            "data" => User::find($w, $rt->order(), $rt->limit())
        ];
    }
}
