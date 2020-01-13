<?php

namespace App\UI;

use Closure;
//class RT extends \RT
class RT2 extends Element
{
    public $columns = [];
    public $data = [];
    public $ajax = null;
    public $response = null;
    public $responsive = true;
    public $cellUrl = null;
    public $pageLength = 25;
    public $selectable = false;

    public $_page = null;
    public $buttons = [];
    public $exports = [];
    public $order = [];

    public function __construct($objects, $page, $config)
    {
        parent::__construct("div");

        $this->setAttribute("is", "rt2");
        $this->response = new RTResponse();

        $this->_page = $page;

        $this->responsive = $config["rt2-responsive"];
    }

    public function order(string $name, $dir = null)
    {
        $this->order[] = ["name" => $name, "dir" => $dir];
        return $this;
    }

    public function add(string $title, string $getter)
    {
        $c = new Column();

        if ($this->_page) {
            $c->title = $this->_page->translate($title);
        } else {
            $c->title = $title;
        }

        $c->descriptor[] = $getter;


        if ($getter instanceof Closure) {
            $c->data = md5(new \ReflectionFunction($getter));
            $c->name = $c->data;
        } else {
            $c->data = $getter;
            $c->name = $getter;
        }

        $c->data = str_replace(["(", ")"], "_", $c->data);
        $this->columns[] = $c;
        return $c;
    }

    public function addEdit()
    {
        $c = $this->response->addEdit();
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = $this->response->addView();
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }


    public function addDel()
    {
        $c = $this->response->addDel();
        $c->noHide();
        $this->columns[] = $c;
        return $c;
    }

    public function addCheckbox($field)
    {
        $c = new Column();
        $c->type = "checkbox";
        $c->name = $field;
        $this->columns[] = $c;
        return $c;
    }

    public function __toString()
    {
        $this->setAttribute(":columns", $this->columns);
        $this->setAttribute(":data", $this->data);
        $this->setAttribute(":ajax", $this->ajax);
        $this->setAttribute(":responsive", $this->responsive ? "true" : "false");
        $this->setAttribute("cell-url", $this->cellUrl);
        $this->setAttribute(":page-length", $this->pageLength);
        $this->setAttribute(":selectable", $this->selectable ? "true" : "false");
        $this->setAttribute(":buttons", $this->buttons);
        $this->setAttribute(":exports", $this->exports);
        $this->setAttribute(":order", $this->order);
        return parent::__toString();
    }

    public function addSubRow($name)
    {
        $c = new Column();
        $c->noHide();
        $c->name = $name;
        $c->type = "sub-row";
        $c->width = "1px";
        $this->columns[] = $c;
        return $c;
    }

    public function validate(RTResponse $r): bool
    {
        foreach ($r->request["columns"] as $col) {
            if (!$col["name"]) return false;
            $c = $this->getColumn($col["name"]);
            if (!$c) {
                return false;
            }
        }

        foreach ($r->order as $order) {
            if (!$order["name"]) return false;
            if(($order["dir"]!="desc") && ($order["dir"]!="asc")) return false;
            $c = $this->getColumn($order["name"]);
            if (!$c) {
                return false;
            }
        }
        return true;
    }

    private function getColumn(string $name)
    {
        foreach ($this->columns as $col) {
            if ($col->name == $name) {
                return $col;
            }
        }
    }
}
