<?php

namespace ALT\R;

use Element\TimePicker;

class FormItemTimePicker extends TimePicker
{

    public function required(string $message = null)
    {
        $node = $this->parentNode;
        if ($node instanceof FormItem) {
            $node->required($message);
        }
        return $this;
    }
}
