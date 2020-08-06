<?php

namespace Element;

use P\HTMLElement;

class FormItem extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("el-form-item");
    }

    public function __get($name)
    {
        if ($name == "rules") {
            if (!$this->hasAttribute(":rules")) {
                $this->setAttribute(":rules", json_encode([]));
            }
            return $this->attributes->getNamedItem(":rules");
        }
        return parent::__get($name);
    }

}
