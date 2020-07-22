<?php

use App\UI\RTRequest;
use App\UI\RTResponse;
use App\User;
use R\Psr7\Request;

class User_list extends App\Page
{
    private function getRT()
    {
        $rt = $this->createRT2([$this, "ds"]);
        //$rt->selectable = true;
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();
        $rt->add("Username", "username")->ss();
        $rt->add("User group", "usergroup_id")->searchMultiple(App\UserGroup::Query());
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
        //$rt->add("Expiry date", "expiry_date")->sort()->searchDate();
        $rt->add("Join date", "join_date")->sort()->searchDate();
        $rt->add("Language", "language")->sort();

        $rt->add("Online", "isonline");
        //$rt->addButton("test")->attr("onClick","window.self.location='User/1/v'");
        // $rt->add("Style","style")->attr("data-format",'json')->attr("collapsed",true);

        /*        $rt->exports[] = "csv";
        $rt->exports[] = "xlsx";
        $rt->buttons[] = ["text" => "Hello", "action" => "onClickHello"];
         */

        //$rt->selectable = true;
        $rt->cellUrl = "User";

        $rt->addDropdown("XLSX", [$this, "getXLSX"]);

        return $rt;
    }

    public function getXLSX(RTRequest $request)
    {
        $request->setDataSource(App\User::Query());
        $xls = new App\XLSX($request->data());
        $xls->add("Username", "username");
        $xls->add("First name", "first_name");
        $xls->add("Last name", "last_name");
        $xls->add("Phone", "phone");
        $xls->add("email", "email");
        $xls->render();
    }


    public function get()
    {

        $this->write($this->getRT());
    }

    public function ds(RTResponse $rt, $t): RTResponse
    {
        if (!$this->getRT()->validate($rt)) {
            throw new Exception("access deny");
        }

        $rt->source = App\User::Query();

        $rt->columns = [
            "status" => "Status()",
            "isonline" => [
                "content" => "isOnline()",
                "format" => "tick"
            ]
        ];
        $rt->key("user_id");
        $rt->add("usergroup_id", function ($obj) {
            $ugs = [];
            foreach ($obj->UserGroup() as $ug) {
                $ugs[] = (string) $ug;
            }
            return implode(",", $ugs);
        })->searchCallback(function ($v) {
            $usergroup_id = implode(",", $v);
            return ["user_id in (select user_id from UserList where usergroup_id in ($usergroup_id))"];
        });
        //$rt->add("isonline", "isOnline()")->format("tick");
        //$rt->add("username", "username")->alink("v");
        if ($t >= 0) $w[] = ["status=:status1", ["status1" => $t]];
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
