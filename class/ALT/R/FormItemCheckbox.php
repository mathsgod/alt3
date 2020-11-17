<?php

namespace ALT\R;

use Element\Checkbox;

class FormItemCheckbox extends Checkbox
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
