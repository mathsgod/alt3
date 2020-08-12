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
        $rt->source = App\User::Query();

        $rt->add("table", function () {


            $t = $this->createT(App\User::Query());
            $t->add("U", "username");

            return $t;
        })->type="html";
        return $rt;
    }
}
