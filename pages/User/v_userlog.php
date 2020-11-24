<?php
// Created By: Raymond Chong
// Created Date: 2013-04-10
// Last Updated:
class User_v_userlog extends App\Page
{
    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);
        $rt->order("userlog_id", "desc");
        $rt->add("Login time", "login_dt")->searchDate()->sort();
        $rt->add("Logout time", "logout_dt");
        $rt->add("IP address", "ip")->ss();
        $rt->add("Result", "result")->ss();
        $rt->add("User agent", "user_agent")->ss();
        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = App\UserLog::Query([
            "user_id" => $this->object()->user_id
        ]);
        return $rt;

    }
}