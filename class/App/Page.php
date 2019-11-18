<?

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Stream;

class Page extends \R\Page
{
    public $data = [];
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function translate(string $str): string
    {
        return $str;
    }

    public function object()
    {
        if ($this->_object) {
            return $this->_object;
        }


        $class = "\\" . $this->module()->class;

        $id = $this->id();
        if (class_exists($class, true)) {
            try {
                $this->_object = new $class($id);
            } catch (\Exception $e) {
                return null;
            }
        }

        return $this->_object;
    }

    public function id()
    {
        $path = $this->request->getURI()->getPath();
        foreach (explode("/", $path) as $q) {
            if (is_numeric($q)) {
                return $q;
            }
        }
    }

    public function module(): Module
    {
        $route = $this->request->getAttribute("route");
        $ps = explode("/", $route->path);
        $ps = array_values(array_filter($ps, "strlen"));

        foreach ($this->app->modules() as $module) {
            if ($module->name == $ps[0]) {
                return $module;
                break;
            }
        }
    }

    protected function createCard(): UI\Card
    {
        return new UI\Card($this);
    }

    protected function createForm(): UI\Form
    {
        return new UI\Form($this);
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute("route");
        ob_start();
        $response = parent::__invoke($request, $response);
        $echo_content = ob_get_contents();
        ob_end_clean();

        $content = "";

        foreach ($request->getHeader("Accept") as $accept) {
            list($media,) = explode(",", $accept);
            switch ($media) {
                case "text/html":

                    $file = $route->file;
                    $pi = pathinfo($file);
                    if (file_exists($template_file = $pi["dirname"] . "/" . $pi["filename"] . ".twig")) {
                        $this->template = $this->app->twig($template_file);

                        $data = $this->data;
                        $content .= (string) $response;
                        $content .= $echo_content;
                        $content .= $this->template->render($data);

                        $response = $response->withHeader("Content-Type", "text/html; charset=UTF-8");
                    } else {
                        $content .= $echo_content;
                        $content .= (string) $response;
                    }
            }
        }

        return $response->withBody(new Stream($content));
    }
}
