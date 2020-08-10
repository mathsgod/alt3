<?php

namespace ALT\Element;

use Element\DatePicker as ElementDatePicker;
use Vue\Objectable;

class DatePicker extends ElementDatePicker implements Objectable
{

    private $_picker_options = [];
    private $_picker_options_name = null;

    private static $NUM = 0;

    public function __construct()
    {
        parent::__construct();
        $this->setAttribute("value-format", "yyyy-MM-dd");
        $this->_picker_options_name = "el_date_picker_" . self::$NUM . "_picker_option";
        self::$NUM++;
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

    public function js_object()
    {
        return [
            $this->_picker_options_name => $this->_picker_options
        ];
    }

    public function addPickerOption(string $name, $option)
    {
        $this->setAttribute(":picker-options", $this->_picker_options_name);
        $this->_picker_options[$name] = $option;
        return $this;
    }
}
