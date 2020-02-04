<?

use Firebase\JWT\JWT;

class System_example_hostlink_fileman extends ALT\Page
{
    public function get()
    {
        $this->addLib("ckeditor/ckeditor");
        $e = $this->createE([]);
        $e->add("ckeditor")->ckeditor("text1");
        $e->add("file man")->fileman("text2");


        $this->write($e);

        return;
        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "api" => "http://192.168.88.108/hostlink-fileman/",
            "root" => "C:\Users\maths\Desktop\web\hostlink-fileman\uploads",
            "type" => "image",
            "url" => "http://192.168.88.108/hostlink-fileman/uploads",
        ];

        $key = "12345678";

        $token = JWT::encode($payload, $key);

        $e->add("ckeditor")->ckeditor("text1")->attr(":config", json_encode([
            "filebrowserImageBrowseUrl" => "http://localhost:8082/?token=$token&source=ckeditor&type=image",
            "filebrowserBrowseUrl" => "http://localhost:8082/?token=$token&source=ckeditor"
        ]));

        $this->write($this->createForm($e));

        $this->data["token"] = $token;
    }
}
