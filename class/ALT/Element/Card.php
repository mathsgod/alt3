<?php

namespace ALT\Element;

use VueScript;

class Card extends \Element\Card
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute("id", "card1");
    }

    public function script(): VueScript
    {
        $id = $this->getAttribute("id");

        foreach ($this->childNodes as $child) {
            if ($child instanceof Form) {
                $script = $child->script();
            }
        }

        if ($script) {
            $script->el = "#" . $id;
        }

        return $script;
    }
}
