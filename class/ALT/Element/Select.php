<?php


namespace ALT\Element;

use Element\Option;
use Element\Select as ElementSelect;
use Traversable;

class Select extends ElementSelect
{
    public function multiple()
    {
        $this->setAttribute("multiple", true);
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


        $option = new Option;
        $this->append($option);

        $option->setAttribute(":label", $label);
        $option->setAttribute(":value", $value);

        $option->setAttribute("v-for", "(item,value) in " . json_encode($data));
        return $option;
    }

    public function required()
    {
        if ($this->parentNode instanceof FormItem) {
            $rules = json_decode($this->parentNode->rules->value);
            $rules[] = [
                "required" => true
            ];
            $this->parentNode->rules->value = json_encode($rules);
        }
        return $this;
    }
}
