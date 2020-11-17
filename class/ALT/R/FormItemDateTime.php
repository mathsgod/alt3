<?php

namespace ALT\R;

use Element\DatePicker;

class FormItemDateTime extends DatePicker
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
