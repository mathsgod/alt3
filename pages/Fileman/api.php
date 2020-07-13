<?php
class Fileman_api extends R\Page
{
    public function get()
    {
        $this->write("fileman api");
    }

    public function post($token)
    {
        $config = $this->app->config["hostlink-fileman"];
        $f = new Fileman\App($token, $config);

        return $f->post($_POST["query"]);
    }

    public function upload_file($token)
    {
        $config = $this->app->config["hostlink-fileman"];
        $f = new Fileman\App($token, $config);

        return $f->uploadFile($_POST["path"], $_FILES["file"]);
    }
}
