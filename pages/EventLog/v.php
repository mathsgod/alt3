<?php
// Created By: Raymond Chong
// Created Date: 19/3/2010
// Last Updated: 09/05/2012
class EventLog_v extends ALT\Page
{
    public function get()
    {
        $obj = $this->object();

        if ($obj->action == "Delete") {
            $class = $obj->class;
            try {
                $o = new $class($obj->id);
            } catch (Exception $e) {
                $this->navbar()->addButton("Restore object", $obj->uri('restore_object'))->addClass('confirm');
            }
        }

        $mv = $this->createV();
        $mv->header("Details");
        $mv->add("Event Log ID", "eventlog_id");
        $mv->add("Action", "action");
        $mv->add("Class", "class");
        $mv->add("ID", "id");
        $mv->add("User", "User()")->alink("v");
        $mv->add("Source", "source")->attr("data-format", 'json');
        $mv->add("Target", 'target')->attr("data-format", 'json');
        $mv->add("Created time", "created_time");
        $this->write($mv);

        if ($obj->action == "Update") {

            $t = $this->createT($obj->getDifferent());
            $t->header->title = "Different";
            $t->add("Field", "field");
            $t->add("From", 'from');
            $t->add("To", 'to');
            $this->write($t);
        }
    }
}
