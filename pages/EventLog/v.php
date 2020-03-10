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
            if (class_exists($class)) {
                try {
                    $o = new $class($obj->id);
                } catch (Exception $e) {
                    $this->navbar->addButton("Restore object", $obj->uri('restore_object'))->addClass('confirm');
                }
            }
        }

        $mv = $this->createV();
        $mv->header("Details");
        $mv->add("Event Log ID", "eventlog_id");
        $mv->add("Action", "action");
        $mv->add("Class", "class");
        $mv->add("ID", "id");
        $mv->add("User", "User()")->alink("v");
        $mv->add("Created time", "created_time");
        $this->write($mv);

        $grid = $this->createGrid([2]);

        $s = $this->createV($obj->source);
        $s->header->title = "Source";
        foreach ($obj->source as  $k => $v) {
            $s->add($k, $k);
        }
        $grid->add($s, [0, 0]);


        $t = $this->createV($obj->target);
        $t->header->title = "Target";
        foreach ($obj->target as  $k => $v) {
            $t->add($k, $k);
        }
        $grid->add($t, [0, 0]);


        $this->write($grid);



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
