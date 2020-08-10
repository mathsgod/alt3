<?php


namespace ALT\Element;

use App\UI\CKEditor;
use Element\Checkbox;
use Element\ElSwitch;
use Vue\Objectable;

class FormItem extends \Element\FormItem implements Objectable
{
    public function ckeditor(string $name)
    {
        $ckeditor = new CKEditor();
        $ckeditor->setAttribute("is", "ckeditor");

        $card = $this->closest("card");
        if ($card) {
            $config = $card->app->config["hostlink-fileman"];
            $basepath = $config["basepath"];
            $ckeditor->setAttribute("basepath", $basepath);
        }

        $ckeditor->setAttribute(":config", json_encode([
            "filebrowserImageBrowseUrl" => "Fileman/?token=&source=ckeditor&type=image",
            "filebrowserBrowseUrl" => "Fileman/?token=&source=ckeditor"
        ]));


        $this->append($ckeditor);

        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $ckeditor->setAttribute("v-model", "$model.$name");
        }
        return $ckeditor;
    }

    public function timeSelect(string $name)
    {
        $time = new TimeSelect();
        $time->setAttribute("name", $name);
        $this->append($time);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $time->setAttribute("v-model", "$model.$name");
        }
        return $time;
    }

    public function timePicker(string $name)
    {
        $time = new TimePicker();
        $time->setAttribute("name", $name);
        $this->append($time);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $time->setAttribute("v-model", "$model.$name");
        }
        return $time;
    }

    public function inputNumber(string $name)
    {
        $input = new InputNumber();
        $input->setAttribute("name", $name);
        $this->append($input);
        $this->setAttribute("prop", $name);
        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $input->setAttribute("v-model", "$model.$name");
        }
        return $input;
    }

    public function textarea(string $name)
    {
        $input = new Input();
        $input->setAttribute("name", $name);
        $input->setAttribute("type", "textarea");
        $input->setAttribute(":autosize", json_encode(["minRows" => 4]));
        $this->append($input);

        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $input->setAttribute("v-model", "$model.$name");
        }

        return $input;
    }

    public function password(string $name)
    {
        $input = new Input();
        $input->setAttribute("name", $name);
        $input->setAttribute("show-password", true);
        $input->setAttribute("autocomplete", "off");
        $this->append($input);


        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $input->setAttribute("v-model", "$model.$name");
        }

        return $input;
    }


    public function email(string $name)
    {
        $input = new Input();
        $input->setAttribute("name", $name);
        $input->setAttribute("type", "email");
        $this->append($input);

        $this->setAttribute("prop", $name);

        if ($this->parentNode instanceof Form) {
            $model = $this->parentNode->getAttribute(":model");
            $input->setAttribute("v-model", "$model.$name");
        }

        return $input;
    }

    public function input(string $name)
    {
        $input = new Input();
        $input->setAttribute("name", $name);
        $input->setAttribute("clearable", true);
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

        $date->addPickerOption("shortcuts", [
            [
                "text" => "Today",
                "onClick" => js("(picker)=>{picker.\$emit('pick', new Date());}")
            ]
        ]);


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

    public function js_object()
    {
        $obj = [];
        foreach ($this->childNodes as $child) {
            if ($child instanceof Objectable) {
                $js_object = $child->js_object();
                foreach ($js_object as $key => $value) {
                    $obj[$key] = $value;
                }
            }
        }
        return $obj;
    }
}
