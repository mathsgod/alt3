<?php

use App\Config;

class Config_user_list extends App\Page
{
    public function get()
    {
        // outp(App\User::find());
        $t = $this->createRTable([$this, "ds"]);
        $t->addDel();
        $t->addEdit();
        $t->add("Name", "name")->ss();
        $t->add("Value", "value");
        $this->write($t);
    }

    public function ds($rt)
    {
        $rt->source = Config::Query();
        return  $rt;
    }
}
