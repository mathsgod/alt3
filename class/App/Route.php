<?php

namespace App;

use R\Psr7\Request;

class Route extends \R\Route
{
    public $app;
    public function __construct(Request $request, App $app)
    {
        $this->app = $app;
        $uri = $request->getUri();
        $this->uri = (string) $uri;
        $this->path = $uri->getPath();
        $this->basePath = $uri->getBasePath();

        if ($this->path == "/") {
            $this->path = "/index";
        }

        if (substr($this->path, -1) == "/") {
            $this->no_index = true;
            $this->path .= "index";
        }

        $this->method = strtolower($request->getMethod());
        parse_str($uri->getQuery(), $this->query);

        // skip id
        $t = [];
        foreach (explode("/", $this->path) as $q) {
            $q = trim($q);
            if (is_numeric($q)) {
                $this->ids[] = $q;
                if (!$this->id) {
                    $this->id = $q;
                }
                continue;
            }
            if ($q) {
                $t[] = $q;
            }
        }

        $this->path = "/" . implode("/", $t);

        $this->psr0($request, $app->loader);

        if (is_readable($this->file)) {
            require_once($this->file);
        }


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

    public function psr0(Request $request, $loader)
    {
        $pi = $this->app->pathinfo();
        $qs = explode("/", $this->path);
        if (!$qs) $qs = [];

        $method = strtolower($request->getMethod());
        $document_root = $pi["document_root"];
        $system_root = $pi["system_root"];
        $base = $request->getURI()->getBasePath();

        $page = $this->app->config["system"]["pages"];
        if (!$page) {
            $page = DIRECTORY_SEPARATOR . "pages";
        }

        while (count($qs)) {
            $path = implode("/", $qs);

            if (file_exists($file = $document_root . $base . $page . $path . "/index.php")) {
                $this->file = $file;
                $this->path = implode("/", $qs) . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            if (file_exists($file = $system_root . $page . $path . DIRECTORY_SEPARATOR . "index.php")) {

                $this->file = $file;
                $this->path = implode("/", $qs) . "/index";
                $this->class = implode("_", $qs) . "_index";
                $this->action = "index";
                $this->method = $method;
                return;
            }

            if (file_exists($file = $document_root . $base . $page . $path . ".php")) {
                $this->file = $file;
                $this->path = $path;
                $this->class = implode("_", $qs);
                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            if (file_exists($file = $system_root . $page . $path . ".php")) {
                $this->file = $file;
                $this->path = implode("/", $qs);
                $this->class = implode("_", $qs);
                $this->action = array_pop($qs);
                $this->method = $method;
                return;
            }

            $method = array_pop($qs);
        }
    }
}
