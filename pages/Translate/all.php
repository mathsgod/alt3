<?php

use App\Translate;

class Translate_all extends ALT\Page
{
    public function post()
    {

        foreach ($_POST["value"] as $lang => $value) {
            $t = new Translate();
            $t->language = $lang;
            $t->name = $_POST["name"];
            $t->module = $_POST["module"];
            $t->action = $_POST["action"];
            $t->value = $value;
            $t->save();
        }

        return ["code" => 200];


    }

    public function modules()
    {
        return [
            "modules" => $this->app->modules(),
            "languages" => $this->app->config["system"]["language"]
        ];
    }

    public function removeData($data)
    {
        $o = new Translate($data["translate_id"]);
        $o->delete();
        /*
        $w = [];
        if ($o->module) {
            $w[] = ["module=?", $o->module];
        } else {
            $w[] = "module is null";
        }

        $w[] = ["name=?", $o->name];

        if($o->action){
            $w[] = ["action=?", $o->action];
        }else{
            $w[] = "action is null";
        }

        foreach (Translate::Find($w) as $t) {
            outp($t);
            //$t->delete();
        }

        die();*/

        return ["code" => 200];
    }
    public function update()
    {
        $o = new Translate($_POST["translate_id"]);
        foreach ($_POST["value"] as $language => $value) {
            $w = [];
            $w[] = ["name=?", $o->name];
            $w[] = $o->action ? ["action=?", $o->action] : "action is null";
            $w[] = ["language=?", $language];
            $w[] = $_POST["module"] ? ["module=?", $_POST["module"]] : "module is null";
            if ($t = Translate::First($w)) {
                $t->action = $_POST["action"];
                $t->name = $_POST["name"];
                $t->value = $value;
                $t->save();
            }
        }
        return ["code" => 200];
    }

    public function getData($module)
    {
        if ($module) {
            $w[] = ["module=?", $module];
        } else {
            $w[] = "module is null";
        }
        $data = [];
        foreach (Translate::Find($w) as $t) {
            if (!$data[$t->action . '\t' . $t->name]) {
                $data[$t->action . '\t' . $t->name] = [
                    "translate_id" => $t->translate_id,
                    "name" => $t->name,
                    "action" => $t->action,
                    "value" => []
                ];
            }

            $data[$t->action . '\t' . $t->name]["value"][$t->language] = $t->value;
        }

        return ["data" => array_values($data)];
    }

    public function data($module)
    {
        if ($module) {
            $w[] = ["module=?", $module];
        } else {
            $w[] = "module is null";
        }
        return ["data" => Translate::find($w)];
    }
}
