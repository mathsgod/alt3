<?php

namespace ALT\R;

use P\HTMLElement;

class FormItemUpload extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("el-upload");
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
