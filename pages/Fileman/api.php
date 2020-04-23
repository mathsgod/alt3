<?

use Firebase\JWT\JWT;

class Fileman_api extends R\Page
{

    public function get()
    {
        $token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODc2MTQ2NjEsImV4cCI6MTU4NzYxODI2MSwicm9vdCI6IkM6XC9Vc2Vyc1wvbWF0aHNcL0Rlc2t0b3BcL3dlYlwvdXBsb2FkcyIsImFwaSI6IlwvYWx0M1wvRmlsZW1hblwvYXBpXC8iLCJ1cmwiOiJcL3VwbG9hZHMifQ.9RcbZIxgbhUrC8IDDVDFZN7F3yDIakKC--CZctXOLdg";
        $config = $this->app->config["hostlink-fileman"];
        $f = new Fileman\App($token, $config);


        print_r($f);

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

        return $f->uploadFile($_POST["path"],$_FILES["file"]);

        return ;
        if (strstr($_POST["path"], "..")) {
            new Error("access deny");
        }

        $key = $this->app->config["hostlink-fileman"]["key"];

        try {
            $payload = (array) JWT::decode($token, $key, ["HS256"]);
        } catch (Exception $e) {
            return ["error" => [
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ]];
        }

        $files = $this->request->getUploadedFiles();
        $file = $files["file"];

        $client_file_name = $file->getClientFileName();
        $ext = pathinfo($client_file_name, PATHINFO_EXTENSION);

        if (in_array($ext, $this->app->config["disallow"])) {
            return ["error" => [
                "message" => "access deny, ext:$ext"
            ]];
        }

        $path = $_POST["path"];
        if (substr($path, -1) != "/") {
            $path .= "/";
        }


        $file->moveTo($payload["root"] . $path . $file->getClientFileName());

        return ["data" => true];
    }
}
