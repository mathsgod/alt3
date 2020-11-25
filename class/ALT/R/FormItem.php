<?php


namespace ALT\R;

use Element\Button;
use Element\FormItem as ElementFormItem;
use Element\Select;
use P\CustomEvent;
use P\Event;
use P\HTMLElement;

class FormItem extends ElementFormItem
{
    public function required(string $message = null)
    {
        $label = $this->getAttribute("label");

        $rules[] = [
            "required" => true,
            "message" => $message ?? "$label is required"
        ];
        $this->setAttribute(":rules", json_encode($rules));

        return $this;
    }

    public function timeSelect(string $name)
    {
        $time = new FormItemTimeSelect();
        $time->setAttribute("name", $name);
        $time->setAttribute("v-model", "scope.form.{$name}");
        $time->setAttribute("value-format", "HH:mm:ss");
        $this->append($time);
        $this->setAttribute("prop", $name);

        return $time;
    }

    public function timePicker(string $name)
    {
        $time = new FormItemTimePicker();
        $time->setAttribute("name", $name);
        $time->setAttribute("v-model", "scope.form.{$name}");
        $time->setAttribute("value-format", "HH:mm:ss");
        $this->append($time);
        $this->setAttribute("prop", $name);

        return $time;
    }

    public function datetime(string $name)
    {
        $date = new FormItemDateTime();
        $date->setAttribute("name", $name);
        $date->setAttribute("v-model", "scope.form.{$name}");
        $date->setAttribute("value-format", "yyyy-MM-dd HH:mm:ss");
        $date->setAttribute("type", "datetime");
        $this->append($date);
        $this->setAttribute("prop", $name);
        return $date;
    }

    public function ace(string $name, string $mode)
    {
        $e = new HTMLElement("ace");
        $e->style->height = "400px";
        $e->style->display = "none";
        $e->setAttribute("name", $name);
        if ($mode) {
            $e->setAttribute("ace-mode", $mode);
        }
        $this->append($e);
        $this->setAttribute("prop", $name);

        return $e;
    }


    public function multiselect(string $name, $data_source = null, $display_member = null, $value_member = null)
    {
        $select = new FormItemSelect();
        $select->setAttribute("v-model", "scope.form.{$name}");
        $this->append($select);

        foreach ($data_source as $r => $v) {
            $option = new HTMLElement("el-option");
            $value = var_get($v, $value_member);
            if (is_numeric($value)) {
                $option->setAttribute(":value", $value);
            } else {
                $option->setAttribute("value", $value);
            }

            $option->setAttribute("label", var_get($v, $display_member));
            $select->append($option);
        }

        $hidden = new HTMLElement("input");
        $hidden->setAttribute("type", "hidden");
        $hidden->setAttribute("name", $name . "[]");
        $hidden->setAttribute("v-for", "(v,index) in scope.form.$name");
        $hidden->setAttribute(":value", "v");
        $hidden->setAttribute(":key", "index");

        $this->append($hidden);


        $this->setAttribute("prop", $name);


        $select->setAttribute("multiple", true);
        $select->style->width = "100%";
        return $select;
    }


    public function checkbox(string $name)
    {
        $cb = new FormItemCheckbox();
        $cb->setAttribute("v-model", "scope.form.{$name}");

        $this->append($cb);
        $this->setAttribute("prop", $name);

        $hidden = new HTMLElement("input");
        $hidden->setAttribute("type", "hidden");
        $hidden->setAttribute("name", $name);
        $hidden->setAttribute(":value", "scope.form.$name?1:0");
        $this->append($hidden);



        return $cb;
    }

    public function tinymce(string $name)
    {
        $tinymce = new HTMLElement("tinymce");
        $tinymce->setAttribute('name', $name);
        $tinymce->setAttribute("v-model", "scope.form.{$name}");

        $this->append($tinymce);
        $this->setAttribute("prop", $name);
        return $tinymce;
    }

    public function datePicker(string $name)
    {
        $date = new FormItemDatePicker();
        $date->setAttribute("name", $name);
        $date->setAttribute("v-model", "scope.form.{$name}");
        $date->setAttribute("value-format", "yyyy-MM-dd");

        $this->append($date);
        $this->setAttribute("prop", $name);
        return $date;
    }

    public function date(string $name)
    {
        $date = new FormItemDate();
        $date->setAttribute("name", $name);
        $date->setAttribute("v-model", "scope.form.{$name}");
        $date->setAttribute("value-format", "yyyy-MM-dd");

        $this->append($date);
        $this->setAttribute("prop", $name);
        return $date;
    }

    public function password(string $name)
    {
        $input = new FormItemInput();
        $input->setAttribute("name", $name);
        $input->setAttribute("v-model", "scope.form.{$name}");
        $input->setAttribute("show-password", true);

        $this->append($input);
        $this->setAttribute("prop", $name);
        return $input;
    }

    public function number(string $name)
    {
        /*         $rules = [];
        $rules[] = ["type" => "email"];
        $this->setAttribute(":rules", json_encode($rules));
 */
        $input = new FormItemInputNumber();
        $input->setAttribute("name", $name);
        $input->setAttribute("v-model", "scope.form.{$name}");

        $this->append($input);
        $this->setAttribute("prop", $name);
        return $input;
    }

    public function email(string $name)
    {
        $rules = [];
        $rules[] = ["type" => "email"];
        $this->setAttribute(":rules", json_encode($rules));

        $input = new FormItemInput();
        $input->setAttribute("name", $name);
        $input->setAttribute("v-model", "scope.form.{$name}");
        $input->setAttribute("type", "email");
        $input->setAttribute("prefix-icon", "el-icon-message");

        $this->append($input);
        $this->setAttribute("prop", $name);
        return $input;
    }

    public function textarea(string $name)
    {
        $input = new FormItemInput();
        $input->setAttribute("name", $name);
        $input->setAttribute("type", "textarea");
        $input->setAttribute("v-model", "scope.form.{$name}");

        $this->append($input);
        $this->setAttribute("prop", $name);
        return $input;
    }


    public function input(string $name)
    {
        $input = new FormItemInput();
        $input->setAttribute("name", $name);
        $input->setAttribute("v-model", "scope.form.{$name}");

        $this->append($input);
        $this->setAttribute("prop", $name);
        return $input;
    }

    public function setAttribute($name, $value)
    {
        if ($name == "prop") {
            $event = new CustomEvent("prop_added", ["detail" => ["name" => $value]]);
            $this->dispatchEvent($event);
        }
        return parent::setAttribute($name, $value);
    }

    public function select(string $name, $data_source = null, $display_member = null, $value_member = null)
    {
        $select = new FormItemSelect();
        $select->setAttribute("v-model", "scope.form.{$name}");
        $select->setAttribute("filterable", true);
        $select->setAttribute("clearable", true);
        $this->append($select);


        foreach ($data_source as $r => $v) {
            if (is_string($v)) { //[value=>label]

                $option = new HTMLElement("el-option");
                if (is_numeric($r)) {
                    $option->setAttribute(":value", $r);
                } else {
                    $option->setAttribute("value", $r);
                }
                $option->setAttribute("label", $v);
            } else {
                $option = new HTMLElement("el-option");
                if ($value_member === null) {
                    $value_member = $name;
                }
                $value = var_get($v, $value_member);
                if (is_numeric($value)) {
                    $option->setAttribute(":value", $value);
                } else {
                    $option->setAttribute("value", $value);
                }

                if ($display_member === null) {
                    $label = (string)$v;
                } else {
                    $label = var_get($v, $display_member);
                }

                $option->setAttribute("label", $label);
            }

            $select->append($option);
        }


        $hidden = new HTMLElement("input");
        $hidden->setAttribute("type", "hidden");
        $hidden->setAttribute("name", $name);
        $hidden->setAttribute("v-model", "scope.form.$name");
        $this->append($hidden);


        $this->setAttribute("prop", $name);
        return $select;
    }

    public function file(string $name)
    {
        $input = new HTMLElement("input");
        $input->setAttribute("name", $name);
        $input->setAttribute("type", "file");
        $this->append($input);

        if ($form = $this->closest("r-form")) {
            $form->setAttribute("enctype", "multipart/form-data");
        }

        return $input;
    }

    public function upload(string $name)
    {
        $upload = new HTMLElement("el-upload");
        $upload->setAttribute("name", $name);
        $upload->setAttribute("action", "https://jsonplaceholder.typicode.com/posts/");
        $upload->setAttribute(":limit", 1);
        $upload->setAttribute(":auto-upload", "false");
        $upload->setAttribute(":file-list", "scope.form.$name");


        $button = new Button();
        $button->textContent = "Select file";
        $upload->append($button);
        $this->append($upload);

        if ($form = $this->closest("r-form")) {
            $form->setAttribute("enctype", "multipart/form-data");
        }

        return $upload;
    }
}
