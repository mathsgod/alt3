<?php
class System_test_rt extends App\Page
{
    public function get()
    {
        $rt = $this->createRT2([$this, "ds"]);
        $rt->add("Username", "username");
        $rt->add("Table", "table");
        $this->write($rt);
    }
    public function ds($rt)
    {
        $rt->source = App\EventLog::Query();

        $rt->add("table", function ($o) {

            $t = $this->createT(App\User::Query()->toArray());
            $t->addEdit();
            $t->addView();
            $t->add("U", "username");
            $t->add("test",function()use($o){
                return $o->eventlog_id;
            });

            return $t;
        })->type="html";
        return $rt;
    }
}
