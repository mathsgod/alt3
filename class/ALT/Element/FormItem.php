<?php


namespace ALT\Element;

use Element\Checkbox;
use Element\ElSwitch;
use P\HTMLInputElement;

class FormItem extends \Element\FormItem
{

    public function input(string $name)
    {
        $input = new Input();
        $input->setAttribute("name", $name);
        $this->append($input);


        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $input->setAttribute("v-model", "$model.$name");
        }

        return $input;
    }

    public function datePicker(string $name)
    {
        $date = new DatePicker();
        $date->setAttribute("name", $name);
        $this->append($date);

        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $date->setAttribute("v-model", "$model.$name");
        }


        return $date;
    }

    public function select(string $name)
    {
        $select = new Select();
        $select->setAttribute("name", $name);
        $this->append($select);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $select->setAttribute("v-model", "$model.$name");
        }

        return $select;
    }

    public function checkbox(string $name)
    {
        $cb = new Checkbox();
        $cb->setAttribute("name", $name);
        $cb->setAttribute("true-label", 1);

        $this->append($cb);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $cb->setAttribute("v-model", "$model.$name");
        }



        return $cb;
    }

    public function checkboxGroup()
    {
        $cbg = new CheckboxGroup();
        $this->append($cbg);
        //        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            //          $cbg->setAttribute("v-model", "$model.$name");
        }

        return $cbg;
    }

    public function switch(string $name)
    {
        $switch = new ElSwitch();
        $switch->setAttribute("active-value", 1);
        $switch->setAttribute("inactive-value", 0);
        $this->append($switch);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $switch->setAttribute("v-model", "$model.$name");
        }

        return $switch;
    }
}
