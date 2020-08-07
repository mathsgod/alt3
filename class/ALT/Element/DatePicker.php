<?php

namespace ALT\Element;

use Element\DatePicker as ElementDatePicker;
use Vue\Script;
use Vue\Scriptable;

class DatePicker extends ElementDatePicker implements Scriptable
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

    public function script()
    {
        $script = new Script;
   /*     $script->data["pickerOptions"] = [];
        $script->data["pickerOptions"]["shortcuts"] = [
            "text" => "Today",
            "onClick" => js("function(picker){
                picker.\$emit('pick',new Date());
            }")
        ];*/
    }
}

