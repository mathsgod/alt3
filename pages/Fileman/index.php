<?php

use Firebase\JWT\JWT;

class Fileman_index extends App\Page
{

    public function get($token)
    {

        if (!$token) {
            $token = $this->getToken();

            $this->redirect("Fileman/?token=" . $token);
        }
    }
    public function getToken()
    {

        $pi = $this->app->pathinfo();
        $composer_base = $pi["composer_base"];
        $document_root = $pi["document_root"];

        $config = $this->app->config["hostlink-fileman"];
        $url = $config["url"] ?? $composer_base . "/vendor/mathsgod/hostlink-fileman/dist";

        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "root" =>  $document_root . "/uploads",
            "api" => "http://127.0.0.1/alt3/Fileman/api",
            "url" => "http://127.0.0.1/uploads"
        ];

        if ($config["root"]) {
            $payload["root"] = $config["root"];
        }

        if ($config["api"]) {
            $payload["api"] = $config["api"];
        }

        $key = $config["key"] ?? session_id();

        $token = JWT::encode($payload, $key);


        return $token;
    }
}
