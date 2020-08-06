<?php


namespace ALT\Element;

class Input extends \Element\Input
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
