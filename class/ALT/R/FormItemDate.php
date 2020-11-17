<?php

namespace ALT\R;

use P\HTMLElement;

class FormItemDate extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("r-date");
    }

    public function required(string $message = null)
    {
        $node = $this->parentNode;
        if ($node instanceof FormItem) {
            $node->required($message);
        }
        return $this;
    }
}
