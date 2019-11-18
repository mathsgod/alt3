<?php

namespace App\UI;

use P\HTMLSpanElement;
use App\Page;

class BoxTools extends HTMLSpanElement
{
    protected $page;
    public function __construct(Page $page)
    {
        parent::__construct();
        $this->page = $page;
        $this->setAttribute("slot", "tools");
    }

    public function addButton()
    {
        $btn = new \BS\Button();
        $btn->classList->remove("btn-default");
        $btn->classList->add("btn-box-tool");
        $this->append($btn);
        return $btn;
    }

    public function addLabel($text)
    {
        $label = new \BS\Label("primary");
        $label->innerText = $text;
        $this->append($label);
        return $label;
    }

    public function addButtonDropdown($label)
    {
        $bg = new ButtonDropdown($this->route);
        $bg->button()->classList->add('btn-box-tool');
        $bg->button()->classList->remove("btn-default");

        p($bg->button())->text($label);

        $this->append($bg);
        return $bg;
    }
}
