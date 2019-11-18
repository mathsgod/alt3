<?php

namespace App;

class Module
{
    public $group;
    public $icon = "far fa-circle nav-icon";
    public $class;
    public $menu = [];
    public $sequence = PHP_INT_MAX;

    public $show_index = true;
    public $show_list = false;
    public $show_create = false;
    public $show_update = true;
    public $show_delete = true;

    public $log = true;

    public $hide = false;

    public function __construct(string $name, array $config = [])
    {
        $this->name = $name;

        foreach ($config as $k => $v) {
            $this->$k = $v;
        }
    }

    public function loadConfig(array $config = [])
    {
        foreach ($config as $k => $v) {
            $this->$k = $v;
        }
    }
    /*
    public function icon()
    {
        if (!$this->icon) {
            return "far fa-circle nav-icon";
        }
        return $this->icon;
    }
*/
    public function __toString()
    {
        return $this->class;
    }


    /*
    public static function ByPath($path)
    {
        $ps = explode("/", $path);
        $ps = array_values(array_filter($ps, "strlen"));
        if ($module = self::All()[$ps[0]]) {
            return $module;
        }
        //return new Module();
    }

    public function getAction()
    {
        $app = self::$_app;

        $page = $app->config["system"]["page"];
        if (!$page) {
            $page = "pages";
        }

        $pi = $app->pathInfo();

        $name = $this->name;
        if (file_exists($file = $pi["cms_root"] . "/pages/" . $name)) {
            foreach (glob($file . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }
        if (file_exists($file = $pi["system_root"] . "/pages/" . $name)) {
            foreach (glob($file . "/*.php") as $p) {
                $pi = pathinfo($p);
                $action[] = $pi;
            }
        }

        return $action;
    }

    public function showCreate()
    {
        if (property_exists($this, "show_create")) {
            return $this->show_create;
        }
        return false;
    }
*/
    public function getMenuLink($path)
    {
        if ($this->hide) {
            return [];
        }
        $links = [];
        foreach ($this->menu as $k => $v) {
            if (is_array($v)) {
                $links[] = [
                    "label" => $this->translate($k),
                    "link" => $v["link"],
                    "icon" => $v["icon"],
                    "active" => ($path == $v["link"]),
                    "target" => $v["target"],
                    "keyword" => $this->translate($k)
                ];
            } else {
                $links[] = [
                    "label" => $this->translate($k),
                    "link" => $v,
                    "icon" => "fa fa-fw fa-link",
                    "active" => ($path == $v),
                    "keyword" => $this->translate($k)
                ];
            }
        }

        if ($this->show_create) {

            $links[] = [
                "label" => $this->translate("Add"),
                "link" => $this->name . "/ae",
                "icon" => "fa fa-fw fa-plus",
                "active" => ($path == $this->name . "/ae"),
                "keyword" => ""
            ];
        }

        if ($this->show_list || $this->show_index) {

            $links[] = [
                "label" => $this->translate("List"),
                "link" => $this->name,
                "icon" => "fa fa-fw fa-list",
                "active" => ($path == $this->name),
                "keyword" => ""
            ];
        }

        return $links;
    }

    public function translate($text)
    {
        return $text;
        $t = Translate::ByModule($this->name, \My::Language());
        return $t[$text] ? $t[$text] : $text;
    }

    public function keyword()
    {
        return $this->name . " " . $this->translate($this->name);
    }
}
