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



        outp($_POST);
        die();

        // delete the removed data
        foreach ($_POST["_d"] as $id) {
            $t = new Translate($id);
            $t->delete();
        }

        foreach ($_POST["_u"] as $id => $c) {
            $t = new Translate($id);
            if ($c["name"] == "") continue;
            foreach ($this->app->config["language"] as $v => $l) {
                $w = [];
                $w[] = "name='$t->name'";
                $w[] = $t->module ? "module='$t->module'" : "module is null";
                $w[] = "language='$v'";
                $tt = Translate::first($w);
                $tt->value = $c["value_" . $v];
                $tt->save();
            }
        }

        foreach ($_POST["_c"] as $c) {
            if ($c["name"] == "") continue;
            foreach ($this->app->config["language"] as $v => $l) {
                $t = new Translate();
                $t->language = $v;
                $t->name = $c["name"];
                $t->action = $c["action"];
                $t->value = $c["value_" . $v];
                $t->module = $_POST["module"];
                $t->save();
            }
        }

        $this->_redirect("Translate");
    }

    public function modules()
    {
        return [
            "modules" => $this->app->getModule(),
            "languages" => $this->app->config["language"]
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
