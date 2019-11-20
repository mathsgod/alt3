<?php

use App\Config;

class Config_update extends App\Page
{

    public function post()
    {
        $value = $_POST["value"];
        $pk = $_POST["pk"];

        if (!$config = Config::First([["name=?", $pk]])) {
            $config = new Config();
            $config->name = $pk;
            $config->type = "text";
        }

        $config->value = $value;

        $config->save();

        return ["code" => 200];
    }
}
