<?php
namespace Xeditable;
use P\HTMLAnchorElement;
class DateTime extends HTMLAnchorElement {
    public function __construct() {
        parent::__construct();
        $this->setAttribute("href", "javascript:void(0)");
        $this->setAttribute("is","x-editable");
        $this->setAttribute("data-mode", "inline");
        $this->setAttribute("data-type", "text");
        $this->setAttribute("data-custom-type", "datetime");
        $this->setAttribute("data-tpl", "<input class='input-sm form-control'>");
    }

    public function __set($name, $value) {
        if ($name == "value") {
            $this->setAttribute("data-value", $value);
            return;
        } elseif ($name == "emptyText") {
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
        }
        parent::__set($name, $value);
    }
}

?>