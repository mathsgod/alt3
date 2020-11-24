<?php

use App\UI\Card;
use App\UI\RTableResponse;
use App\UI\RTRequest;

class System_example_list2 extends ALT\Page
{

    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);

        $rt->setCellUrl("User");

        $rt->add("", "subrow1");
        $rt->add("Username", "username")->ss()->width("10px");
        

        $rt->add("Usergroup", "usergroup_id")->searchable("multiselect")->searchOption(App\UserGroup::Query());
        $rt->add("Status", "status")->searchable("select")->searchOption(App\User::STATUS);

        $rt->add("First name", "first_name")->editable();
        $rt->add("Join date", "join_date")->searchable("date")->editable("date");
        $rt->add("card", "card");

        $rt->selectable();

        //        $rt->addDropdown("XLSX", [$this, "getXLSX"], $_GET);

        $this->write($rt);
    }


    public function getXLSX(RTRequest $request)
    {
        $query = App\User::Query();
        $request->setDataSource($query);

        $request->setSearchCallback("usergroup_id", function ($v) {
            $usergroup_id = implode(",", $v);
            return ["user_id in (select user_id from UserList where usergroup_id in ($usergroup_id))"];
        });

        $xls = new App\XLSX($request->data());
        $xls->add("Username", "username");
        $xls->add("First name", "first_name");
        $xls->add("Last name", "last_name");
        $xls->add("Phone", "phone");
        $xls->add("email", "email");
        $xls->render();
    }

    public function ds(RTableResponse  $rt)
    {
        $rt->source = App\User::Query();

        $that = $this;
        $rt->add("card", function () use ($that) {
            $t = $that->createT(App\User::Query());
            $t->add("Username", "username");
            return (string)$t;
        })->type = "vue";

        $rt->addSubRow("subrow1", [$this, "subrow1"], "user_id");

        $rt->setKey("user_id");
        //      $rt->add("status", "Status()")->setCellValue("status");
        $rt->add("status", "Status()");

        $rt->add("usergroup_id", function ($obj) {
            $ugs = [];
            foreach ($obj->UserGroup() as $ug) {
                $ugs[] = (string) $ug;
            }
            return implode(",", $ugs);
        })->searchCallBack(function ($v) {
            $usergroup_id = implode(",", $v);
            return ["user_id in (select user_id from UserList where usergroup_id in ($usergroup_id))"];
        });

        return $rt;
    }

    public function subrow1()
    {
        $this->write("this is a subrow1");
    }
}
