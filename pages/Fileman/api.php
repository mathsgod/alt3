<?

use Firebase\JWT\JWT;

class Fileman_api extends App\Page
{

    public function get()
    {
        print_r($this->app->config["hostlink-fileman"]);

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
