<?php

namespace App\UI;

use P\HTMLDivElement;
use App\Page;
use BS\Button;

class CardHeader extends HTMLDivElement
{
    protected $page;
    public $tools;

    public function __construct(Page $page)
    {
        parent::__construct();
        $this->page = $page;
        $this->setAttribute("is", "card-header");

        $this->tools = new BoxTools($page);
        $this->append($this->tools);
    }

    public function addButton($label, $uri)
    {
        $button = new Button("default", "btn-xs", $uri);
        $button->classList[] = "btn-xs";
        $button->text($label);
        $this->appendChild($button);
        return $button;
    }

    public function __set($name, $value)
    {
        if ($name == "title") {
            $template = p("template");
            $template->text($value);
            $template->attr("#title", true);
            $this->prepend($template);
            return;
        }
        parent::__set($name, $value);
    }
}
