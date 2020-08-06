<?php

namespace ALT\Element;

use Element\TimePicker as ElementTimePicker;

class TimePicker extends ElementTimePicker
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute(":picker-options", json_encode([
            "selectableRange" => "00:00:00 - 23:59:59"
        ]));
    }
}
