<?php

namespace ALT\Element;

use Element\DatePicker as ElementDatePicker;

class DatePicker extends ElementDatePicker
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute("value-format", "yyyy-MM-dd");
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
