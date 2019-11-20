<?php
/*
Created by: Raymond Chong
Created time: 27/6/2016 16:13:35
*/

class MailLog_list extends App\Page {
    public function get() {
        $rt = $this->createRT([$this, "ds"]);
        $rt->key("maillog_id");
        $rt->order("maillog_id", "desc");
    	$rt->add("Subject", "subject");

        $rt->add("From", "from");
    	$rt->add("To", "to");

        $rt->add("Created time", "created_time");

        $rt->subHTML([$this, "content"]);
        $this->write($rt);
    }

    public function content($id) {
        $o = new App\MailLog($id);
        $src = $o->uri("body");

        $this->write("<iframe width='100%' src='$src'></iframe>");
    }

    public function ds($r) {
        $w = $r->where();

        return ["total" => App\MailLog::Count($w),
        "data" => App\MailLog::Find($w, $r->order(), $r->limit())];
    }
}