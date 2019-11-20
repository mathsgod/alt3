<?php
namespace Xeditable;
use \P\HTMLAnchorElement;
class Select extends HTMLAnchorElement {
    public function __construct() {
    	parent::__construct();
        $this->setAttribute("href", "javascript:void(0)");
        $this->setAttribute("is","x-editable");
        $this->setAttribute("data-mode", "inline");
        $this->setAttribute("data-type", "select");
    }
    
    public function setAttribute($name,$value){
    	if($name=="source"){
			$source = [];
    		if(is_array($value)){
                foreach($value as $k => $v) {
                    $source[] = ["value" => $k, "text" => $v];
                }
    		}
    		return $this->setAttribute("data-source",$source);
    	}
    	return parent::setAttribute($name,$value);
    }
    
    public function __set($name, $value) {
        if ($name == "value") {
            $this->attr("data-value", $value);
            return;
        } elseif ($name == "source") {
            if (is_array($value)) {
                $source = [];
                foreach($value as $k => $v) {
                    $source[] = ["value" => $k, "text" => $v];
                }

                $this->attr("data-source", json_encode($source));
            } else {
                $this->attr("data-source", $value);
            }

            return;
        } elseif ($name == "emptyText") {
            $this->attr("data-emptyText", $value);
            return;
        } elseif ($name == "title") {
            $this->attr("data-title", $value);
            return;
        } elseif ($name == "pk") {
            $this->attr("data-pk", $value);
            return;
        } elseif ($name == "name") {
            $this->attr("data-name", $value);
            return;
        } elseif ($name == "url") {
            $this->attr("data-url", $value);
            return;
        }
        parent::__set($name, $value);
    }
}
