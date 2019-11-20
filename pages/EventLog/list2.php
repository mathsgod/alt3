<?php
// Created By: Raymond Chong

use App\EventLog;
use App\User;

class EventLog_list2 extends ALT\Page
{
    public function get()
    {

        $rt = $this->createRT2([$this, "ds"]);

        //$rt->order("eventlog_id", "desc");
        //$rt->addView();

        $rt->addCheckbox("cb_id");

        //$rt->add("ID", "eventlog_id")->ss();
        //$rt->add("Class", "class")->ss();
        $rt->add("Object ID", "id")->ss();

        /*$rt->add("Action", "action")->ss();
        $rt->add("User", "user_id")->searchOption(User::find());
        $rt->add("Created time", "created_time")->sort()->searchDate();
*/
        $rt->buttons[] = [
            "title" => "Submit",
            "action" => "onClickSubmit",
            "class" => "btn btn-xs btn-primary"
        ];
        $this->write($rt);
    }

    public function ds($rt)
    {
        $rt->source = EventLog::Query();
        //$rt->add("user_id", "User()")->alink("v");

        $rt->add("cb_id", "eventlog_id");
        return $rt;
    }
}
