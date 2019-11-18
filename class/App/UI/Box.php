<?php

namespace App\UI;

use App\Page;
use P\HTMLDivElement;

class BoxClassTokenList extends \P\DOMTokenList
{
    public function offsetSet($offset, $value)
    {
        $values = $this->values();
        if ($this->values()) {
            if (in_array($value, BOX::BOX_CLASS)) {
                $this->value = implode(" ", array_diff($values, BOX::BOX_CLASS));
            }
        }
        parent::offsetSet($offset, $value);
    }
}

class Box extends HTMLDivElement
{
    const BOX_CLASS = ["box-default", "box-primary", "box-success", "box-info", "box-warning", "box-danger"];

    const ATTRIBUTES = [
        "dataUrl" => ["name" => "data-url"],
        "dataUri" => ["name" => "data-uri"],
        "collapsible" => ["name" => ":collapsible", "type" => "json"],
        "collapsed" => ["name" => ":collapsed", "type" => "json"],
        "pinable" => ["name" => ":pinable", "type" => "json"],
        "draggable" => ["name" => ":draggable", "type" => "json"]
    ] + parent::ATTRIBUTES;

    protected $page;
    private static $NUM = 0;

    public function __construct(Page $page)
    {
        parent::__construct();
        $this->page = $page;

        $this->setAttribute("is", "alt-box");
        $this->classList->add("box");

        $this->dataUri = $page->path() . "/box[" . self::$NUM . "]";

        $ui = \App\UI::_($this->dataUri);
        if ($ui->layout) {
            $layout = json_decode($ui->layout, true);
            if ($layout["collapsed"]) {
                $this->collapsed = $layout["collapsed"];
            }
        }

        self::$NUM++;
    }

    public function collapsible(bool $collapsible)
    {
        $this->collapsible = $collapsible;
        return $this;
    }

    public function pinable(bool $pinable)
    {
        $this->pinable = $pinable;
        return $this;
    }

    public function __get($name)
    {
        if ($name == "header") {
            $this->header = new BoxHeader($this->page);
            $this->prependChild($this->header);
            return $this->header;
        }

        if ($name == "body") {
            $this->body = new BoxBody($this->page);
            $this->appendChild($this->body);
            return $this->body;
        }

        if ($name == "footer") {
            $this->footer = new BoxFooter($this->page);
            $this->appendChild($this->footer);
            return $this->footer;
        }

        switch ($name) {
            case "classList":
                if (!$this->hasAttribute("class")) {
                    $this->setAttribute("class", "");
                }
                return new BoxClassTokenList($this->attributes->getNamedItem("class"));
                break;
        }
        return parent::__get($name);
    }

    public function body()
    {
        return p($this->body);
    }

    public function header($title = null)
    {
        if ($title) {
            $this->header->title = $title;
        }
        return p($this->header);
    }
}
