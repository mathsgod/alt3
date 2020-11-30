<?php
// Created By: Raymond Chong
// Created Date: 5/5/2010
// Last Updated:
use App\SystemValue;

class SystemValue_list extends App\Page
{
    public function get()
    {
        
        $rt = $this->createRTable([$this, "ds"]);

        $rt->addEdit();
        $rt->addDel();
        $rt->Order("name", "asc");
        $rt->add("Name", "name")->ss();

        foreach ($this->app->languages() as $v) {
            $rt->add($v, "value_$v");
        }

        $this->write($rt);
    }
    public function ds($rt)
    {
        $rt->source = SystemValue::Query(["language" => "en"]);

        foreach ($this->app->languages() as $v) {
            $rt->add("value_$v", function ($obj) use ($v) {
                return nl2br(SystemValue::_($obj->name, $v));
            })->type = "html";
        }

        return $rt;
    }
}
