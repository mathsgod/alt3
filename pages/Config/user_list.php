<?php
use App\Config;

class Config_user_list extends App\Page
{
    public function get()
    {
        // outp(App\User::find());
        $t = $this->createRT([$this,"ds"]);
        $t->addDel();
        $t->addEdit();
        $t->add("Name", "name")->search()->sort();
        $t->add("Value", "value");
        $this->write($t);
    }

    public function ds($rt)
    {
        $w=$rt->where();
        $data["total"]=Config::Count($rt->where($w));
        $data["data"]=Config::Find($rt->where($w), $rt->order(), $rt->limit());
        return $data;
    }
}
