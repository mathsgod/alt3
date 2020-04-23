<?php

use Firebase\JWT\JWT;

class Fileman_index extends App\Page
{

    public function get($token)
    {

        if (!$token) {
            $token = $this->getToken();

            $query = $_GET ?? [];
            $query["token"] = $token;
            $q = http_build_query($query);


            $this->redirect("Fileman/?" . $q);
        }
    }
    public function getToken()
    {

        $pi = $this->app->pathinfo();
        $document_root = $pi["document_root"];


        $config = $this->app->config["hostlink-fileman"];

        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "root" =>  $document_root . "/uploads",
            "api" => $this->app->base_path . "/Fileman/api/",
            "url" => $config["basepath"]
        ];

        if ($config["root"]) {
            $payload["root"] = $config["root"];
        }

        if ($config["api"]) {
            $payload["api"] = $config["api"];
        }

        $key = $config["key"];

        $token = JWT::encode($payload, $key);


        return $token;
    }
}
