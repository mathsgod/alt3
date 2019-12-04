<?php
class System_example_createE extends ALT\Page
{
    public function post()
    {
        outp($_POST);
    }

    public function get()
    {
        $e = $this->createE($this->app->user);
        $e->add("Username")->input("username");
        $e->add("remark")->textarea("remark");

        $e->add("Username")->input("username")->required();
        $e->add("remark")->textarea("remark")->required();

        $this->write($this->createForm($e));

        $e = $this->createE([
            "m" => "a1,a2,a3",
            "s" => "a1",
            "m2" => "a1,a2,a3",
            "s2" => "a1",
            "cb1" => 0,
            "m3" => "a1,a2,a3",
            "m4" => "a1",
        ]);

        $e->add("multi1")->multiSelect("m")->options(["a1", "a2", "a3", "a4"]);
        $e->add("single1")->select("s")->options(["a1", "a2", "a3", "a4"]);

        $e->add("multi2")->multiSelectPicker("m2")->options(["a1", "a2", "a3", "a4"]);
        $e->add("single")->selectPicker("s2")->options(["a1", "a2", "a3", "a4"]);
        $e->add('checkbox')->checkbox("cb1");
        $e->add("multiSelect2")->multiSelect2("m3")->options(["a1", "a2", "a3", "a4"]);
        $e->add("select2")->select2("m4")->options(["a1", "a2", "a3", "a4"]);
        $this->write($this->createForm($e));

        $e = $this->createE([
            "date" => date("Y-m-d"),
            "dt" => date("Y-m-d H:i"),
            "t" => date("H:i")
        ]);
        $e->add("Date")->date("date");
        $e->add("Date (required)")->date("date")->required();
        $e->add("Date Time")->datetime("dt");
        $e->add("Date Time (required)")->datetime("dt")->required();
        $e->add("Date Time")->time("t");
        $e->add("Date Time (required)")->time("t")->required();

        $this->write($this->createForm($e));
    }
}
