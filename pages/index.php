
<?

use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;

class _index extends R\Page
{
    public function get()
    {
        $twig = $this->app->twig(__DIR__ . "/index.twig");

        $pi = $this->app->pathInfo();

        $data = $pi;

        $this->write($twig->render($data));
        //print_r($this->app->loader);

        //$this->write("index");
    }
}
