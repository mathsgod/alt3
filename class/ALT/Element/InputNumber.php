<?php


namespace ALT\Element;

use Element\InputNumber as ElementInputNumber;

class InputNumber extends ElementInputNumber
{
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
