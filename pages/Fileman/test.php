<?php

use Firebase\JWT\JWT;

class Fileman_test extends ALT\Page
{
    public function get()
    {
        $pi = $this->app->pathinfo();

        $document_root = $pi["document_root"];
        $config = $this->app->config["hostlink-fileman"];

        $payload = [
            "iat" => time(),
            "exp" => time() + 36000,
            "root" =>  $document_root . "/uploads",
            "api" => "http://127.0.0.1/hostlink-fileman/api/",
            "url" => "http://127.0.0.1/uploads"
        ];
        $token = JWT::encode($payload, $config["key"]);

        outp($payload);
        echo $token;
        return;



        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODc1NDc0NzAsImV4cCI6MTU4NzU1MTA3MCwicm9vdCI6IkM6XC9Vc2Vyc1wvbWF0aHNcL0Rlc2t0b3BcL3dlYlwvdXBsb2FkcyIsImFwaSI6Imh0dHA6XC9cLzEyNy4wLjAuMVwvYWx0M1wvRmlsZW1hblwvYXBpIiwidXJsIjpudWxsfQ.rJrn4Wukk0FCtItm_avJZ5OqCJDf4P14Urs3kzcdVc0";

        $config = $this->app->config["hostlink-fileman"];
        $f = new Fileman\App($token, $config);

        //return $f->post($_POST["query"])
        $r = $f->post(
            <<<GQL
query{
    listDirectory(path:"/"){
        name
        path
    }       
}     
        
GQL
        );

        outp($r);
        die();
    }
}
