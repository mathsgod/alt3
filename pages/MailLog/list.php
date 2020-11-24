<?php
/*
Created by: Raymond Chong
Created time: 27/6/2016 16:13:35
*/

class MailLog_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);

        $rt->order("maillog_id", "desc");
        $rt->addSubrow("subrow1");
        $rt->add("ID", "maillog_id")->sortable();
        $rt->add("Subject", "subject");
        $rt->add("From", "from");
        $rt->add("To", "to");

        $rt->add("Created time", "created_time");

        $this->write($rt);
    }

    public function content($maillog_id)
    {
        $o = new App\MailLog($maillog_id);
        $src = $o->uri("body");

        $this->write("<iframe width='100%' src='$src'></iframe>");
    }

    public function ds($rt)
    {

        $rt->addSubRow("subrow1", [$this, "content"], "maillog_id");
        $rt->source = App\MailLog::Query();
        return $rt;
    }
}
