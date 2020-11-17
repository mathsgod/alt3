<?php

namespace ALT\R;

use Closure;
use P\HTMLElement;

class Form extends HTMLElement
{
    private $template;
    private $_data;
    private $page;

    public function __construct()
    {
        parent::__construct("r-form");
        $this->template = new HTMLElement("template");
        $this->template->setAttribute("slot-scope", "scope");
        $this->append($this->template);
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function add(string $label, $getter = null)
    {
        $form_item = new FormItem();
        $this->template->append($form_item);

        if ($this->page) {
            $t_label = $this->page->translate($label);
        } else {
            $t_label = $label;
        }


        $form_item->setAttribute("label", $t_label);


        $form_item->addEventListener("prop_added", function ($e) {
            $detail = $e->detail;
            $name = $detail["name"];

            if ($this->_data) {
                $data = json_decode($this->getAttribute(":data"), true);
                $data[$name] = var_get($this->_data, $name);
                $this->setAttribute(":data", json_encode($data, JSON_UNESCAPED_UNICODE));
            }
        });

        if ($getter) {
            if ($getter instanceof Closure) {
                p($form_item)->html($getter($this->_data));
            } else {
                p($form_item)->text(var_get($this->_date, $getter));
            }
        }

        return $form_item;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }
}
