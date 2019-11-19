<?php

namespace My\HTML;
use My\Func;
class Select extends \My\Query {
    public function __construct($index="") {
        parent::__construct("select");
        if ($index)$this->attr("index", $index);
        $this->addClass("form-control");
    }
    
	public function optGroup($data,$getter){
		$this->attr("optgroup",$getter);
		foreach($data as $i=>$k){
			 p("optgroup")->attr("index",$i)->attr("label", $k)->appendTo($this);
		}
		return $this;
	}

    public function options($options) {
        $this->childNodes = array();
        foreach($options as $k => $o) {
            if (is_array($o)) {
                $og = p("optgroup")->attr("label", $k)->appendTo($this);
                foreach($o as $opt) {
                    $option = p("option")->appendTo($og);
                    $option->text($opt);
                    $option->val($opt);
                }
            } else {
                $option = p("option");
                $option->text($o);
                $option->val($o);
                $this->append($option);
            }
        }
        return $this;
    }

    public function ds($datasource, $display_member = null, $value_member = null) {
        if (!$value_member) {
            $value_member = $this->attr("index");
        }

        foreach($datasource as $key => $o) {
            $option = p("option");
            if (is_object($o)) {
                $option->text(Func::_($display_member)->call($o));
                $option->val(Func::_($value_member)->call($o));
            } else {
                $option->text($o);
                $option->val($key);
            }

        	//check optgroup
        	if($optgroup_getter=$this->attr("optgroup")){
        		$optgroup_value=Func::_($optgroup_getter)->call($o);
        		foreach($this->find("optgroup") as $optgroup){
        			if($optgroup->attributes["index"]==$optgroup_value){
        				$optgroup->appendChild($option[0]);
        			}
        		}

        	}else{
        		$this->append($option);
        	}


        }

        return $this;
    }
}