<?php

use App\UI\Card;
use App\UI\RTableResponse;

class System_example_list2 extends ALT\Page
{

    public function get()
    {
        $rt = $this->createRTable([$this, "ds"]);

        $rt->setCellUrl("User");

        $rt->add("Username", "username")->ss();
        $rt->add("Status", "status")->searchable("select")->searchOption(App\User::STATUS);

        $rt->add("First name", "first_name")->editable();
        $rt->add("Join date", "join_date")->searchable("date")->editable("date");

        $this->write($rt);
    }

    public function ds(RTableResponse  $rt)
    {
        $rt->source = App\User::Query();
        $rt->setKey("user_id");
        //      $rt->add("status", "Status()")->setCellValue("status");
        $rt->add("status", "Status()");
        return $rt;
    }
}
