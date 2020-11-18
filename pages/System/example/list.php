<?php

use App\UI\RTResponse;

class System_example_list extends ALT\Page
{

    public function  get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->add("Username", "username");
        $rt->add("Status", "status")->editable("select", App\User::STATUS);
        $this->write($rt);
    }

    public function ds(RTResponse $rt)
    {
        $rt->source = App\User::Query();
        $rt->add("status", "status")->setCellValue("status");
        return $rt;
    }
}
