<?php

namespace ALT\R;

use Element\TimeSelect;

class FormItemTimeSelect extends TimeSelect
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
