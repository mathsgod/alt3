<?php

namespace ALT;

use Exception;

class InputSelect extends \P\HTMLDivElement
{
    public $menu = null;
    public function __construct()
    {
        parent::__construct();
        $this->classList->add("input-group");

        $div = p("div");
        $div->addClass("input-group-prepend");

        $button = p("button");
        $button->attr("type", "button");
        $button->addClass("btn btn-outline-secondary dropdown-toggle");
        $button->attr("data-toggle", "dropdown");
        $div->append($button);

        $this->menu = new DropdownMenu();
        $div->append($this->menu);


        p($this)->append($div);

        $input = p("input");
        $input->attr("type", "text");
        $input->addClass("form-control");
        p($this)->append($input);
    }

    public function addItem(string $value)
    {
        $item = $this->menu->addItem($value);

        $item->attr("onClick", "$(this).closest('.input-group').find('input').val($(this).text());");

        return $item;
    }

    public function ds(array $a)
    {
        throw new Exception("function ds is deprecated");
    }
}
