<?php

namespace ALT\R;

use Element\Input;

class FormItemInput extends Input
{

    public function required(string $message = null)
    {
        $node = $this->parentNode;
        if ($node instanceof FormItem) {
            $node->required($message);
        }
        return $this;
    }

    public function placeholder(string $placeholder)
    {
        $this->setAttribute("placeholder", $placeholder);
        return $this;
    }
}
