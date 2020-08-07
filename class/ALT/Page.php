<?php

namespace ALT;

use Psr\Http\Message\ResponseInterface;
use Exception;
use R\Psr7\Request;
use Vue\Scriptable;

class Page extends \App\Page
{
    public $navbar = null;
    public $callout;
    protected $header;
    public $master;
    public $name;

    public function __construct(\App\App $app)
    {
        parent::__construct($app);
        $this->navbar = new Navbar($this);
        $this->callout = new Callout();
        $this->header = new PageHeader;
    }

    public function __invoke(Request $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;

        $route = $this->request->getAttribute("route");
        $this->name = $route->action;
        foreach ($this->module()->menu as $name => $action) {
            if ($action["link"] == substr($route->path, 1)) {
                $this->name = $name;
            }
        }

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $this->master = new MasterPage($this->app);
            if ($this->module()) {
                $this->header->title = $this->module()->name;
            }

            if ($referer = $this->request->getHeader("Referer")[0]) {

                $uri = $this->request->getUri()->withUserInfo(null, null);
                if ($referer != (string) $uri) { //reload
                    $_SESSION['app']["referer"][(string) $uri] = $referer;
                }
            }
        }

        try {
            $response = parent::__invoke($request, $response);
        } catch (Exception $e) {
            $this->alert->danger("Error", $e->getMessage());
            if (!$this->master) {
                return $this->redirect();
            }
        }

        if ($this->master) {
            if ($route->action == "ae") {
                if ($route->id) {
                    $this->name = "Edit";
                } else {
                    $this->name = "Add";
                }
            }

            $this->name = $this->translate($this->name);
            $this->master->data["page"] = $this;
            $this->master->data["callouts"] = $this->callout;
            $this->master->data["header"] = $this->header;
            $this->master->data["module"] = $this->module();

            $this->master->data["content"] .= (string) $response;
            if ($this->navbar->hasButton()) {
                $this->master->data["navbar"] = $this->navbar;
            }

            $this->master->data["plugins"] = array_values($this->plugins);

            $pi = $this->app->pathinfo();
            foreach (glob($pi["cms_root"] . "/js/*.js") as $js) {
                $this->master->data["custom_js"][] = "js/" . basename($js);
            }

            foreach (glob($pi["cms_root"] . "/css/*.css") as $js) {
                $this->master->data["custom_css"][] = "css/" . basename($js);
            }

            if (is_readable($pi["cms_root"] . "/AdminLTE/custom-header.html")) {
                $this->master->data["custom_header"] = file_get_contents($pi["cms_root"] . "/AdminLTE/custom-header.html");
            }


            return $this->master->__invoke($request, $response);
        }
        return $response;
    }

    public function createE($object = null): \App\UI\E
    {
        if (func_num_args() == 0) {
            $object = $this->object();
        }
        return new \App\UI\E($object, $this);
    }

    public function createGrid(array $sizes = [1]): Grid
    {
        $route = $this->request->getAttribute("route");
        $action = $route->action;

        $grid = new Grid($sizes);
        $uri = $this->module()->name . "/" . $action . "/grid[" . $grid->attr('grid-num') . "]";
        $grid->attr("data-uri", $uri);
        // load layout
        $ui = $this->app->ui($uri);

        $grid->layout = $ui->layout();



        return $grid;
    }

    public function navbar(): Navbar
    {
        return $this->navbar;
    }

    public function write($element)
    {
        parent::write($element);
        if ($element instanceof Scriptable) {
            parent::write($element->script());
        }
    }
}
