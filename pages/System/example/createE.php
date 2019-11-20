<?php
class System_example_createE extends ALT\Page
{
    public function get()
    {
        $e = $this->createE($this->app->user);
        $e->add("Username")->input("username");
        $this->write($e);
        //$this->write($this->createForm($e));


        $e = $this->createE([
            "m" => "a1,a2,a3",
            "s" => "a1",
            "m2" => "a1,a2,a3",
            "s2" => "a1"
        ]);
        $e->add("multi1")->multiSelect("m")->options(["a1", "a2", "a3", "a4"]);
        $e->add("single1")->select("s")->options(["a1", "a2", "a3", "a4"]);

        $e->add("multi2")->multiSelectPicker("m2")->options(["a1", "a2", "a3", "a4"]);
        $e->add("single")->selectPicker("s2")->options(["a1", "a2", "a3", "a4"]);

        $this->write($e);
    }
}
