<?php
use App\UserLog;
use App\User;

class UserLog_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);

        $rt->order("userlog_id", "desc");
        $rt->add("ID", "userlog_id")->sort()->searchEq();
        $rt->add("User", "user_id")->searchOption(User::find());
        $rt->add("Login time", "login_dt")->sort()->searchDate();
        $rt->add("Logout time", "logout_dt")->sort()->searchDate();
        $rt->add("IP address", "ip")->sort()->search();
        $rt->add("Result", "result")->sort()->searchOption(array("SUCCESS" => "SUCCESS", "FAIL" => "FAIL"));
        $rt->add("User agent", "user_agent")->search();

        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = UserLog::Query();
        $rt->add("user_id", "User()");

        return $rt;

    }
}