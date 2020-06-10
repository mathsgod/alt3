<?php
class System_example_select2 extends ALT\Page
{
    public function post()
    {
        outp($_POST);
        die();
    }

    public function get()
    {
        $e = $this->createE([
            "select2" => 0,
            "m2" => "0,1",
            "ms" => "0,1"

        ]);
        
        $e->add("select2")->select2("select2")->ds(["a", "b", "c"])->attr(":options", json_encode([
            "placeholder" => "abcp1"
        ]));


        $e->add("multiSelect2")->multiSelect2("m2")->ds(["a", "b", "c"])->attr(":options", json_encode([
            "placeholder" => "abcp1"
        ]));


        $e->add("multiSelect")->multiSelect("ms")->ds(["a", "b", "c"]);

        $this->write($this->createForm($e));
    }
}
