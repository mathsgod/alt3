<?php

namespace App;

use Psr\Http\Message\RequestInterface as RequestInterface;

class Route extends \R\Route
{
    public $app;
    public function __construct(RequestInterface $request, App $app)
    {
        $this->app = $app;
        $uri = $request->getUri();
        $this->uri = (string) $uri;
        $this->path = $uri->getPath();
        $this->basePath = $app->base_path;
        $this->path = substr($this->path, strlen($app->base_path));

        $this->method = strtolower($request->getMethod());
        parse_str($uri->getQuery(), $this->query);


        // skip id
        $t = [];
        foreach (explode("/", $this->path) as $q) {
            if (is_numeric($q)) {
                $this->ids[] = $q;
                if (!$this->id) {
                    $this->id = $q;
                }
                continue;
            }
            $t[] = $q;
        }

        $this->path = implode("/", $t);
        $this->psr0($request, $app->loader);
        //  outp($this);
        // die();
        if ($this->file) {
            require_once($this->file);

            if (class_exists($this->class, false)) {
                $app->loader->addClassMap([$this->class => $this->file]);
                return;
            }

            if ($this->class[0] == "_") {
                $class = substr($this->class, 1);
                if (class_exists($class, false)) {
                    $this->class = $class;
                    $app->loader->addClassMap([$class => $this->file]);
                    return;
                }
            }
        }
    }

    public function psr0($request, $loader)
    {
        $pi = $this->app->pathinfo();

        $method = strtolower($request->getMethod());
        $document_root = $pi["document_root"];
        $system_root = $pi["system_root"];
        $base = $this->app->base_path;

        $page = $this->app->config["system"]["pages"];
        if (!$page) {
            $page = DIRECTORY_SEPARATOR . "pages";
        }

        $qs = explode("/", $this->path);
        $qs = array_filter($qs, "strlen");

        //end with slash, check index
        if (substr($this->path, -1) == "/") {
            $file = $document_root . $base .  $page .  $this->path . "index.php";
            if (file_exists($file)) {
                $this->file = $file;
                $this->path = $this->path . "index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            $file = $system_root . $page . $this->path . "index.php";
            if (file_exists($file)) {
                $this->file = $file;
                $this->path = $this->path . "index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }
        } else {
            $file = $document_root . $base .  $page .  $this->path . "/index.php";
            if (file_exists($file)) {
                $this->file = $file;
                $this->path = $this->path . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            $file = $system_root . $page . $this->path . "/index.php";
            if (file_exists($file)) {
                $this->file = $file;
                $this->path = $this->path . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }
        }

        while (count($qs)) {
            $path = "/" . implode("/", $qs);
            if (file_exists($file = $document_root . $base . $page . $path . ".php")) {
                $this->file = $file;
                $this->path = $path;
                $this->class = implode("_", $qs);
                if (count($qs) == 1) {
                    $this->class = "_" . $this->class;
                }

                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            if (file_exists($file = $system_root . $page . $path . ".php")) {
                $this->file = $file;
                $this->path = $path;
                $this->class = implode("_", $qs);
                if (count($qs) == 1) {
                    $this->class = "_" . $this->class;
                }

                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            $method = array_pop($qs);
        }
    }

    public function __debugInfo()
    {
        $arr = get_object_vars($this);
        unset($arr["app"]);
        return $arr;
    }
}
