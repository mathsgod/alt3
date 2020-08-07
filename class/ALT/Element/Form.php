<?php

namespace ALT\Element;

use Vue\Scriptable;
use VueScript;

class Form extends \Element\Form implements Scriptable
{
    private $last_form_item;

    protected static $NUM = 0;
    public function __construct()
    {
        $id = "_el_form_" . self::$NUM;
        parent::__construct();
        $this->setAttribute("method", "POST");
        $this->setAttribute(":model", "el_form_" . self::$NUM);
        $this->setAttribute("id", $id);
        $this->setAttribute("ref", $id);
        $this->setAttribute("label-width", "auto");

        //-- button
        $item = new FormItem();
        $this->append($item);
        $btn = new \Element\Button();
        $btn->setAttribute("type", "primary");
        $btn->setAttribute("v-on:click", $id . "_submit_form");
        $btn->textContent = "Submit";

        $item->append($btn);
        $this->last_form_item = $item;

        self::$NUM++;
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

        $script = new \Vue\Script();
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

        $script->methods["{$id}_submit_form"] = <<<js
function(){
            this.\$refs.$id.validate((valid) => {
                if (valid) {
                    var form=this.\$refs.$id;
                    this.\$http.post(form.\$el.action,this.{$model}).then(resp=>{
                        console.log(resp);
                    });
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
