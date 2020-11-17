<?php

namespace ALT\R;

use Element\Option;
use Element\Select;
use Traversable;

class FormItemSelect extends Select
{

    public function required(string $message = null)
    {
        $node = $this->parentNode;
        if ($node instanceof FormItem) {
            $node->required($message);
        }
        return $this;
    }
    
    public function option($source, string $label = "item", string $value = "value")
    {
        $data = [];
        if ($source instanceof Traversable) {
            foreach ($source as $d) {
                $data[] = [
                    "label" => var_get($d, $label),
                    "value" => var_get($d, $value)
                ];
            }
            $label = "item.label";
            $value = "item.value";
        } else {
            $data = $source;
        }


        $option = new Option();
        $this->append($option);

        $option->setAttribute(":label", $label);
        $option->setAttribute(":value", $value);

        $option->setAttribute("v-for", "(item,value) in " . json_encode($data, JSON_UNESCAPED_UNICODE));
        return $option;
    }
}
