<?php

namespace App\UI;

use App\Page;
use P\HTMLDivElement;

class CardClassTokenList extends \P\DOMTokenList
{
    public function offsetSet($offset, $value)
    {
        $values = $this->values();
        if ($this->values()) {
            if (in_array($value, Card::CARD_CLASS)) {
                $this->value = implode(" ", array_diff($values, Card::CARD_CLASS));
            }
        }
        parent::offsetSet($offset, $value);
    }
}

class Card extends HTMLDivElement
{
    const CARD_CLASS = ["card-default", "card-primary", "card-success", "card-info", "card-warning", "card-danger"];

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

        //$this->classList->add("card");
        $this->setAttribute("is", "card");


        /*        $ui = \App\UI::_($this->dataUri);
        if ($ui->layout) {
            $layout = json_decode($ui->layout, true);
            if ($layout["collapsed"]) {
                $this->collapsed = $layout["collapsed"];
            }
        }

        self::$NUM++;*/
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
            $this->header = new CardHeader($this->page);
            $this->prependChild($this->header);
            return $this->header;
        }

        if ($name == "body") {
            $this->body = new CardBody($this->page);
            $this->appendChild($this->body);
            return $this->body;
        }

        if ($name == "footer") {
            $this->footer = new CardFooter($this->page);
            $this->appendChild($this->footer);
            return $this->footer;
        }

        switch ($name) {
            case "classList":
                if (!$this->hasAttribute("class")) {
                    $this->setAttribute("class", "");
                }
                return new CardClassTokenList($this->attributes->getNamedItem("class"));
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
