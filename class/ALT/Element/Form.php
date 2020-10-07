<?php

namespace ALT\Element;

use ReflectionObject;
use Vue\Objectable;
use Vue\Scriptable;

class Form extends \Element\Form implements Scriptable
{
    private $last_form_item;

    private $_page;
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
        $this->setAttribute("v-loading", "el_form_" . self::$NUM . "_loading");

        //-- button
        $item = new FormItem();
        $this->append($item);

        //-- submit
        $btn = new \Element\Button();
        $btn->setAttribute("type", "success");
        $btn->setAttribute("v-on:click", $id . "_submit_form");
        $btn->textContent = "Submit";
        $btn->setAttribute("icon", "el-icon-check");
        $item->append($btn);

        //-- back
        $btn = new \Element\Button();
        $btn->setAttribute("type", "warning");
        $btn->setAttribute("v-on:click", $id . "_click_back");
        $btn->textContent = "Back";
        $item->append($btn);

        $this->last_form_item = $item;

        self::$NUM++;
    }

    public function add(string $label): FormItem
    {

        if ($this->page) {
            $t_label = $this->page->translate($label);
        } else {
            $t_label = $label;
        }

        $formitem = new FormItem();
        $formitem->setAttribute("label", $t_label);
        $this->insertBefore($formitem, $this->last_form_item);
        return $formitem;
    }

    public function setData($data)
    {
        $model = $this->getAttribute(":model");
        $this->data = $data;

        $r = new ReflectionObject($data);
        $class = $r->getShortName();

        $gql_url = "graphql";
        if ($r->getNamespaceName() == "App") {
            $gql_url .= "?system=1";
        }

        if ($data->id()) {
            $key = $data::_key();
            $this->gql_action = "mutation";
            $this->gql_field = "update$class";
            $this->gql_field_id = $data->id();
            $id = $data->id();

            $this->gql_function = <<<js
            await this.\$gql.mutation(location.toString(),{
                update$class:{
                    __args:{
                        {$key}:{$id},
                        data:this.{$model}
                    }
                }
            })
js;
        } else {
            $this->gql_action = "subscription";
            $this->gql_field = "create$class";
            $this->gql_function = <<<js
            await this.\$gql.subscription(location.toString(),{
                create$class:{
                    __args:this.{$model}
                }
            })
js;
        }
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function script()
    {
        $id = $this->getAttribute("id");
        $model = $this->getAttribute(":model");

        $script = new \Vue\Script();
        $script->el = "#$id";

        //loop all form item
        $data = [];
        foreach ($this->childNodes as $child) {
            if ($child instanceof Objectable) {
                $js_object = $child->js_object();
                foreach ($js_object as $key => $value) {
                    $script->data[$key] = $value;
                }
            }

            if ($child instanceof FormItem) {
                $prop = $child->getAttribute("prop");
                if ($prop) {
                    $data[$prop] = var_get($this->data, $prop);
                }
            }
        }

        $script->data[$model] = $data;
        $script->data[$model . "_loading"] = false;

        $script->methods = [];

        $click_back = <<<JS
function(){
    window.history.go(-1);
}
JS;
        $script->methods["{$id}_click_back"] = js($click_back);

        $submit_form = <<<JS
async function(){
    try{
        await this.\$refs.$id.validate();
    }catch(e){
        return;
    }
       
    var form=this.\$refs.$id;
    this.{$model}_loading=true;

    var resp={$this->gql_function};
    var r=resp.data;
    console.log(r);

    if(r.error){
        this.{$model}_loading=false;
        this.\$alert(r.error.message);
        return;
    }

    if(r.data.headers){
        if(r.data.headers.location){
            window.self.location=r.data.headers.location;
        }
    }else{
        if(document.referrer){
            window.self.location=document.referrer;
            return;
        }

        this.{$model}_loading=false;
    }
 
}
JS;
        $script->methods["{$id}_submit_form"] = js($submit_form);
        return $script;
    }
}
