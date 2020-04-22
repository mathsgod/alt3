<?

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
        outp($config);
        $f = new Fileman\App($token, $config);
        
 //       return $f->post($_POST["query"]);
    }
}
