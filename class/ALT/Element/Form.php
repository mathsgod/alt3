<?php

namespace ALT\Element;

use VueScript;

class Form extends \Element\Form
{
    private $last_form_item;
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute(":model", "form1");
        $this->setAttribute("method", "POST");
        $this->setAttribute("id", "form1");
        $this->setAttribute("ref", "form1");
        $this->setAttribute("label-width", "auto");


        //-- button

        $item = new FormItem();
        $this->append($item);

        $btn = new \Element\Button();
        $btn->setAttribute("type", "primary");
        $btn->setAttribute("v-on:click", "submitForm");
        $btn->textContent = "Submit";


        $item->append($btn);
        $this->last_form_item = $item;
    }

    public function add(string $label): FormItem
    {
        $formitem = new FormItem();
        $formitem->setAttribute("label", $label);
        $this->insertBefore($formitem, $this->last_form_item);
        return $formitem;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function script()
    {
        $id = $this->getAttribute("id");
        $model = $this->getAttribute(":model");

        $script = new VueScript();
        $script->el = "#$id";

        //loop all form item
        $data = [];
        foreach ($this->childNodes as $form_item) {
            if ($form_item instanceof FormItem) {
                $prop = $form_item->getAttribute("prop");
                if ($prop) {
                    $data[$prop] = var_get($this->data, $prop);
                }
            }
        }

        $script->data[$model] = $data;


        $script->methods = [];
        $script->methods["submitForm"] = <<<js
function(){
            this.\$refs.$id.validate((valid) => {
                if (valid) {
                    
                    var form = document.createElement("form");
                    form.enctype="multipart/form-data";
                    form.method="POST";
                    for(var key in this.$model){
                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", key);
                        if(this.{$model}[key]==null){
                            hiddenField.setAttribute("value","");
                        }else{
                            hiddenField.setAttribute("value", this.{$model}[key]);
                        }
                        form.appendChild(hiddenField);
                    }
                    document.body.appendChild(form);
                    form.submit();
                    
                    //this.\$refs.$id.\$el.submit();
                } else {
                    console.log('error submit!!');
                    return false;

                }
            });
        }
js;
        return $script;
    }
}
