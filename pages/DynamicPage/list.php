<?php

//Created by Raymond Chong
//Date: 2020-06-26
class DynamicPage_list extends App\Page
{
    public function get()
    {
        $rt2 = $this->createRT2([$this, "ds"]);
        $rt2->addView();
        $rt2->addEdit();
        $rt2->addDel();
        $rt2->add("Date", "input");
        $rt2->add("Name", "name");
        $rt2->add("Path", "path");

        $this->write($rt2);
    }
    public function ds($rt)
    {
        $rt->source = App\DynamicPage::Query();
        $rt->add("input", function ($o) {
            return html("a")->href($o->uri("edit_data"))->class("btn btn-primary btn-xs")->text('input');
        })->type = "html";
        return $rt;
    }
}
