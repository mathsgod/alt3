<?php

use App\UI\RTResponse;

class System_example_list extends App\Page
{

    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->add("Username", "username");
        $rt->add("Status", "status")->editable("select", App\User::STATUS);

        $rt->setCellEditUrl("System/example/testpost");
        $this->write($rt);
    }

    public function ds(RTResponse $rt)
    {
        $rt->source = App\User::Query();
        $rt->key("user_id");
        $rt->add("status", "Status()")->setCellValue("status");
        return $rt;
    }
}
