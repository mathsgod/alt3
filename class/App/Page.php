<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use R\Psr7\Stream;
use Exception;
use R\Psr7\ServerRequest;

class Page extends \R\Page
{
    /**
     * @var \App\App
     */
    public $app;
    public $data = [];
    protected $alert;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->alert = $app->alert;
    }

    public function translate(string $str): string
    {
        if ($module = $this->module()) {
            return $module->translate($str);
        }
        return $this->app->translate($str);
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

    public function module()
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
        $tab = new UI\Tab($this, $prefix);
        $tab->classList->add("card-primary");
        return $tab;
    }

    protected function createRT2($objects): UI\RT2
    {
        $rt = new UI\RT2($this, $this->app->config);
        $rt->ajax["url"] = (string) $objects[0]->request->getURI()->getPath() . "/" . $objects[1] . "?" . $this->request->getUri()->getQuery();
        $rt->ajax["url"] = substr($rt->ajax["url"], 1);


        $rt->pageLength = 25;
        return $rt;
    }

    protected function createCard(string $type = ""): UI\Card
    {
        $card = new UI\Card($this);
        $card->classList->add("card-outline");
        if ($type) {
            $card->classList->add("card-$type");
        }

        return $card;
    }

    protected function createForm($content): UI\Form
    {
        $form = new UI\Form($this);
        p($form->card->body)->append($content);
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

    public function delete()
    {
        $obj = $this->object();
        if ($obj->canDelete()) {
            $obj->delete();
        }

        if ($this->request->isAccept("application/json")) {
            return ["code" => 200];
        } else {
            $this->alert->success($this->module()->name . " deleted");
            $this->redirect();
        }
    }

    protected $plugins = [];
    public function addLib(string $library)
    {
        $name = $library;
        if ($this->plugins[$name]) {
            return $this->plugins[$name];
        }
        $p = new Plugin($name, $this->app);

        foreach ($p->setting["require"] as $require) {
            $this->addLib($require);
        }

        foreach ($p->setting["php"] as $php) {
            include_once($p->base . "/" . $php);
        }

        $this->plugins[$name] = $p;
        if ($name == "ckeditor") {
            $path = $this->app->config["user"]["roxy_fileman_path"];
            $path = str_replace("{username}", $this->app->user->username, $path);
            $_SESSION["roxy_fileman_path"] = $path;

            $pi = $this->app->pathinfo();
            $path = $pi["system_root"] . $path;
            mkdir($path);
        }


        return $p;
    }

    public function __invoke(ServerRequest $request, ResponseInterface $response): ResponseInterface
    {
        $route = $request->getAttribute("route");

        $path = substr($route->path, 1);
        if (!$this->app->acl($path)) {
            return $this->app->accessDeny($request);
        }


        if ($request->getQueryParams()["_rt"]) {
            $rt = new UI\RTResponse();
            $request = $request->withQueryParams(["rt" => $rt]);
        }

        if($request->getQueryParams()["_rt_request"]){
            $rt = new UI\RTRequest($request);
            $request = $request->withQueryParams(["request" => $rt]);
        }

        if ($request->isAccept("text/html") && $request->getMethod() == "get") {
            $file = $route->file;
            $pi = pathinfo($file);
            if (file_exists($template_file = $pi["dirname"] . "/" . $pi["filename"] . ".twig")) {
                $this->template = $this->app->twig($template_file);
            }
        }
        try {
            ob_start();
            $response = parent::__invoke($request, $response);
            $echo_content = ob_get_contents();
            ob_end_clean();
        } catch (Exception $e) {
            $body = new Stream();
            $body->write(json_encode(["error" => [
                "message" => $e->getMessage()
            ]], JSON_UNESCAPED_UNICODE));
            return $response->withHeader("Content-Type", "application/json; charset=UTF-8")->withBody($body);
        }

        $content = "";

        if ($request->getQueryParams()["_rt"]) {
            return $response;
        }

        if ($request->getQueryParams()["_rt_request"]) {
            return $response;
        }


        foreach ($request->getHeader("Accept") as $accept) {
            list($media,) = explode(",", $accept);
            switch ($media) {
                case "application/json":
                    return $response->withHeader("Content-Type", "application/json; charset=UTF-8");
                    break;
                case "*/*":
                case "text/html":
                    if ($this->template) {
                        $data = $this->data;
                        $data["app"] = $this->app;
                        $content .= (string) $response;
                        $content .= $echo_content;
                        $content .= $this->template->render($data);

                        $response = $response->withHeader("Content-Type", "text/html; charset=UTF-8");
                    } else {
                        $content .= $echo_content;
                        $content .= (string) $response;
                    }

                    if ($request->getMethod() == "get") {
                        $content .= $request->getAttribute("included_content");
                    }

                    break;
            }
        }

        return $response->withBody(new Stream($content));
    }

    public function post()
    {
        $obj = $this->object();
        $id = $obj->id();

        if ($id) { //update
            if (!$obj->canUpdate()) {
                throw new Exception("access deny");
            }
        }

        $data = $this->request->getParsedBody();

        $obj->bind($data);

        if ($files = $this->request->getUploadedFiles()) {
            foreach ($files as $name => $file) {

                if (property_exists($obj, $name)) {
                    $obj->$name = (string) $file->getStream();
                }
                if (property_exists($obj, $name . "_name")) {
                    $obj->{$name . "_name"} = $file->getClientFilename();
                }

                if (property_exists($obj, $name . "_type")) {
                    $obj->{$name . "_type"} = $file->getClientMediaType();
                }

                if (property_exists($obj, $name . "_size")) {
                    $obj->{$name . "_size"} = $file->getSize();
                }
            }
        }



        $obj->save();
        if ($this->request->isAccept("application/json") || $this->request->getHeader("X-Requested-With")) {
            return ["code" => 200];
        } else {

            $msg = $this->module()->name . " ";
            if (method_exists($obj, '__toString')) {
                $msg .= (string) $obj . " ";
            }
            $msg .= $id ? "updated" : "created";
            $this->alert->success("Success", $msg);
            $this->redirect();
        }
    }

    public function redirect(string $uri = null): ResponseInterface
    {
        if ($uri) {
            $location = $this->request->getUri()->getBasePath() . "/" . $uri;
            $this->response = $this->response->withHeader("Location", $location);
            return $this->response;
        }

        if ($_GET["_referer"]) {
            $this->response = $this->response->withHeader("Location", $_GET["_referer"]);
            return $this->response;
        }

        if ($referer = $this->request->getHeader("Referer")[0]) {
            if ($url = $_SESSION["app"]["referer"][$referer]) {
                $this->response = $this->response->withHeader("Location", $url);
                return $this->response;
            }
            $this->response = $this->response->withHeader("Location", $referer);
            return $this->response;
        }
    }

    public function createDataTable($objects): UI\DataTables
    {
        return new UI\DataTables($objects, $this);
    }
}
