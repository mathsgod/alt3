<?php
/*
Created by: Raymond Chong
Created time: 27/6/2016 16:13:35
*/

class MailLog_list extends App\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);

        $rt->order("maillog_id", "desc");
        $rt->addSubRow("subrow1");
        $rt->add("Subject", "subject");

        $rt->add("From", "from");
        $rt->add("To", "to");

        $rt->add("Created time", "created_time");

        $this->write($rt);
    }

    public function content($id)
    {
        $o = new App\MailLog($id);
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
