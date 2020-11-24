<?php

namespace ALT\R;


use Element\InputNumber;

class FormItemInputNumber extends InputNumber
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
