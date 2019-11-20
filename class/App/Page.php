<?

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use R\Psr7\Stream;
use R\Psr7\JSONStream;

class Page extends \R\Page
{
    public $data = [];
    protected $alert;
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->alert = $app->alert;
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

    public function path()
    {
        $route = $this->request->getAttribute("route");
        return substr($route->path, 1);
    }

    protected function createTab($prefix = null): UI\Tab
    {
        return new UI\Tab($this, $prefix);
    }

    protected function createRT2($objects, $module = null): UI\RT2
    {
        $rt = new UI\RT2(null, $this, $this->app->config);
        $rt->ajax["url"] = (string) $objects[0]->request->getURI()->getPath() . "/" . $objects[1] . "?" . $this->request->getUri()->getQuery();
        $rt->ajax["url"] = substr($rt->ajax["url"], 1);


        $rt->pageLength = 25;
        return $rt;
    }

    protected function createCard(): UI\Card
    {
        return new UI\Card($this);
    }

    protected function createForm($content): UI\Form
    {
        $form = new UI\Form($this);
        $form->card->body->append($content);
        return $form;
    }

    public function createV($object = null): UI\V
    {
        if (!$object) {
            $object = $this->object();
        }
        return new UI\V($object, $this);
    }

    public function createT($objects): UI\T
    {
        return new UI\T($objects, $this);
    }

    public function createTable($objects): UI\Table
    {
        return new UI\Table($objects, $this);
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute("route");


        if ($request->getQueryParams()["_rt"]) {
            $rt = new UI\RTResponse();
            $request = $request->withQueryParams(["rt" => $rt]);
        }


        ob_start();
        $response = parent::__invoke($request, $response);
        $echo_content = ob_get_contents();
        ob_end_clean();

        $content = "";

        if ($request->getQueryParams()["_rt"]) {
            return $response;
        }


        foreach ($request->getHeader("Accept") as $accept) {
            list($media,) = explode(",", $accept);
            switch ($media) {
                case "application/json":
                    return $response
                        ->withHeader("Content-Type", "application/json; charset=UTF-8")
                        ->withBody(new JSONStream($this->object()));
                    break;
                case "*/*";
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
                    break;
            }
        }

        return $response->withBody(new Stream($content));
    }
}
