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
        $obj = $this->object();

        $data = [];
        $data["name"] = $obj->name;

        foreach ($this->app->languages() as $l) {
            if ($obj->name) {
                $data["value"][$l] = (string) SystemValue::_($obj->name, $l);
            } else {
                $data["value"][$l] = "";
            }
        }

        $mv = $this->createE($data);
        if ($obj->systemvalue_id) {
            $mv->add("Name", "name");
        } else {
            $mv->add("Name")->input("name")->required();
        }

        foreach ($this->app->languages() as $v) {
            $mv->add("Value " . $v)->textarea("value[$v]"); //->attr("is", "ace");
        }


        $f = $this->createForm($mv);
        if ($obj->systemvalue_id) {
            $f->addHidden("name", $obj->name);
        }

        $this->write($f);
    }
}
