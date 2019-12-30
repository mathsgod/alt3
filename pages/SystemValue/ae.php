<?php
// Created By: Raymond Chong
use App\SystemValue;

class SystemValue_ae extends ALT\Page
{
    public function post()
    {
        foreach ($_POST["value"] as $lang => $val) {
            $obj = SystemValue::_($_POST["name"], $lang);
            if (!$obj) {
                $obj = new SystemValue();
                $obj->name = $_POST["name"];
                $obj->language = $lang;
            }
            $obj->value = $val;
            $obj->save();
        }
        $this->redirect();
    }

    public function get()
    {
        //$this->addLib("ace");
        $obj = $this->object();

        $data = [];
        $data["name"] = $obj->name;
        foreach ($this->app->config["language"] as $v => $l) {
            if ($obj->name) {
                $data["value[$v]"] = (string) SystemValue::_($obj->name, $v);
            } else {
                $data["value[$v]"] = "";
            }
        }

        $mv = $this->createE($data);
        $mv->add("Name")->input("name")->required();


        foreach ($this->app->config["language"] as $v => $l) {
            $mv->add("Value " . $l)->textarea("value[$v]"); //->attr("is", "ace");
        }


        $this->write($this->createForm($mv));
    }
}
