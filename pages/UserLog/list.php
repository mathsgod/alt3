<?php

use App\UserLog;
use App\User;

class UserLog_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);

        $rt->order("userlog_id", "desc");
        $rt->add("ID", "userlog_id")->sortable()->searchable("equal");
        $rt->add("User", "user_id")->searchOption(User::Query());
        $rt->add("Login time", "login_dt")->sortable()->searchable("date");
        $rt->add("Logout time", "logout_dt")->sortable()->searchable("date");
        $rt->add("IP address", "ip")->ss();
        $rt->add("Result", "result")->sortable()->searchable("select")->searchOption(array("SUCCESS" => "SUCCESS", "FAIL" => "FAIL"));
        $rt->add("User agent", "user_agent")->searchable();

        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = UserLog::Query();
        $rt->add("user_id", "User()");

        return $rt;
    }
}
