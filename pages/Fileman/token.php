<?php

use Firebase\JWT\JWT;

class Fileman_token extends ALT\Page
{
    public function post()
    {
        $config = $this->app->config["hostlink-fileman"];
        $token = JWT::encode($_POST, $config["key"]);

        $this->write($token);
    }

    public function get()
    {
        $config = $this->app->config["hostlink-fileman"];

        $pi = $this->app->pathinfo();
        $document_root = $pi["document_root"];



        $payload = [
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

        $e = $this->createE($payload);
        $e->add("root")->input("root");
        $e->add("api")->input("api");
        $e->add("url")->input("url");
        $this->write($this->createForm($e));
    }
}
