<?php
class System_example_createRT2  extends ALT\Page
{
    public function get()
    {
        $rt2 = $this->createRT2([$this, "ds"]);
        $rt2->addCheckbox("user_id");
        $rt2->add("Username", "username");
        $this->write($rt2);
    }

    public function ds($rt)
    {
        $rt->source = App\User::Query();

        return $rt;
    }
}
