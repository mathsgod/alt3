<?php

namespace ALT\Element;

use Element\Checkbox;
use Element\CheckboxGroup as ElementCheckboxGroup;

class CheckboxGroup extends ElementCheckboxGroup
{

    public function checkbox(string $name, $source)
    {

        $cb = new Checkbox();

        $this->append($cb);
        $cb->setAttribute("v-for", "item in " . json_encode($source));
        $cb->setAttribute(":label", "item");
        $cb->setAttribute("name", $name);
    }
}
