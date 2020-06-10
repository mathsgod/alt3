<?php

namespace ALT;

class ButtonGroup extends \P\HTMLDivElement
{
    public function __construct(Page $page)
    {
        parent::__construct();
        $this->classList->add("btn-group");
    }

    public function addA($value = null): \P\Query
    {
        $a = $this->ownerDocument->createElement("a", $value);

        $this->appendChild($a);


        $a->classList->add("btn btn-primary");

        return p($a);
    }

    public function addButton($value = null): \P\Query
    {
        $btn = $this->ownerDocument->createElement("button", $value);

        $this->appendChild($btn);

        $btn->classList->add("btn btn-primary");

        return p($btn);
    }
}
