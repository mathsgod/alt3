<?php
namespace Xeditable;

use P\HTMLAnchorElement;

class Textarea extends HTMLAnchorElement
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute("href", "javascript:void(0)");
        $this->setAttribute("is", "x-editable");
        $this->setAttribute("data-mode", "inline");
        $this->setAttribute("data-type", "textarea");
    }

    public function __set($name, $value)
    {
        if ($name == "emptyText") {
            $this->setAttribute("data-emptyText", $value);
            return;
        } elseif ($name == "title") {
            $this->setAttribute("data-title", $value);
            return;
        } elseif ($name == "pk") {
            $this->setAttribute("data-pk", $value);
            return;
        } elseif ($name == "name") {
            $this->setAttribute("data-name", $value);
            return;
        } elseif ($name == "url") {
            $this->setAttribute("data-url", $value);
            return;
        } elseif ($name == "escape") {
            $this->setAttribute("data-escape", $value);
            return;
        }
        parent::__set($name, $value);
    }
}

