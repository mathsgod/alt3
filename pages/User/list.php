<?php

use App\UI\RTableResponse;
use App\UI\RTRequest;
use App\UI\RTResponse;
use App\User;
use R\Psr7\Request;

class User_list extends App\Page
{
    private function getRT()
    {

        $rt = $this->createRTable([$this, "ds"]);
        //$rt->selectable = true;
        $rt->addView();
        $rt->addEdit();
        $rt->addDel();
        $rt->add("Username", "username")->ss();
        $rt->add("User group", "usergroup_id")->searchable("multiselect")->searchOption(App\UserGroup::Query());
        $rt->add("First name", "first_name")->ss();
        $rt->add("Last name", "last_name")->ss();
        $rt->add("Phone", "phone")->ss()->editable();
        $rt->add("Email", "email")->ss(); //->overflow("hidden");
        $rt->add("Status", "status")->sortable()->searchOption(User::STATUS);

        //        $rt->add("Status", "Status()")->index("status")->sort()->searchOption(User::$_Status);
        // $rt->add("Expiry date", "expiry_date")->sort()->searchDateRange();
        $rt->add("Join date", "join_date")->sortable()->searchable("date");
        $rt->add("Language", "language")->sortable();

        $rt->add("Online", "isonline");
        $rt->add("2-Step", "two_step");
        //$rt->addButton("test")->attr("onClick","window.self.location='User/1/v'");
        // $rt->add("Style","style")->attr("data-format",'json')->attr("collapsed",true);

        /*        $rt->exports[] = "csv";
        $rt->exports[] = "xlsx";
        $rt->buttons[] = ["text" => "Hello", "action" => "onClickHello"];
         */

        //$rt->selectable = true;
        $rt->cellUrl = "User";

        $rt->addDropdown("XLSX", [$this, "getXLSX"], $_GET);


//        $rt->header->innerHTML .= "<button>aa</button>";
//        $rt->header->innerHTML .= "<button>bb</button>";
        return $rt;
    }

    public function getXLSX(RTRequest $request)
    {
        $query = App\User::Query();
        if ($_GET['t'] >= 0) {
            $query->filter(["status" => $_GET["t"]]);
        }
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

    public function get()
    {

        $this->write($this->getRT());
    }

    public function ds(RTableResponse $rt, $t)
    {
        /*     if (!$this->getRT()->validate($rt)) {
            throw new Exception("access deny");
        }
 */
        $rt->source = App\User::Query();

        $rt->columns = [
            "status" => "Status()",
            "isonline" => [
                "content" => "isOnline()",
                "format" => "tick"
            ]
        ];
        $rt->setKey("user_id");
        $rt->add("two_step", "secret")->format("tick");
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
        $rt->source = App\User::Query();
        if ($t >= 0) {
            $rt->source->where("status=:status", ["status" => $t]);
        }

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
