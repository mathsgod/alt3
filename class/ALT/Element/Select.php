<?php


namespace ALT\Element;

use Element\Option;
use Element\Select as ElementSelect;

class Select extends ElementSelect
{

    public function option($source)
    {
        $option = new Option;
        $this->append($option);

        $option->setAttribute(":label", "item.label");
        $option->setAttribute(":value", "item.value");

        $option->setAttribute("v-for", "item in " . json_encode($source));
        return $option;
    }

    public function required()
    {
        if ($this->parentNode instanceof FormItem) {
            $rules = json_decode($this->parentNode->rules->value);
            $rules[] = [
                "required" => true
            ];
            $this->parentNode->rules->value = json_encode($rules);
        }
        return $this;
    }
}
