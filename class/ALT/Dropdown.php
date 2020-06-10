<?php

namespace ALT;

class Dropdown extends \P\HTMLDivElement
{
    public $button = null;
    public $menu = null;

    public function __construct(string $label = null)
    {
        parent::__construct();
        $this->classList->add("btn-group");

        $this->button = $this->ownerDocument->createElement("button", $label);
        $this->button->classList->add("btn dropdown-toggle");
        $this->button->setAttribute("data-toggle", "dropdown");
        $this->appendChild($this->button);

        $this->menu = new DropdownMenu();

        $this->appendChild($this->menu);
    }


    public function addItem($item, $href): \P\Query
    {
        return $this->menu->addItem($item, $href);
    }
}
