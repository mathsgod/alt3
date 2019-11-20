<?php
use App\FormBuilder;

class System_formbuilder extends ALT\Page
{
    public function post()
    {

        $obj=FormBuilder::_($_POST["name"]);
        $obj->bind($_POST);
        $obj->save();
    }

    public function get()
    {
        //$this->write("test");


        $e=$this->createE();
        $e->add("Name")->input("name")->required();

        return ["form1"=>$e];
    }
}
