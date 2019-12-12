<?php

use App\Config;

class Config_update extends App\Page
{

    public function post()
    {

        $body = $this->request->getBody();
        $data = json_decode((string) $body, true);

        $name = array_keys($data)[0];
        $value = $data[$name];

        if (!$config = Config::First([["name=?", $name]])) {
            $config = new Config();
            $config->name = $name;
            $config->type = "text";
        }

        $config->value = $value;

        $config->save();

        return ["code" => 200];
    }
}
