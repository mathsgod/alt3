<?php

namespace App\UI;

use App\Page;
use P\HTMLFormElement;

class Form extends HTMLFormElement
{

    private $submit_button;
    private $back_button;
    private $reset_button;
    protected $page;
    public $card;

    public function __construct(Page $page)
    {
        parent::__construct();
        //$this->setAttribute("is"]="alt-form"
        $this->page = $page;
        $this->method = "post";

        $this->box = new Card($page);
        $this->box->body;
        $this->box->classList->add("box-primary");

        $this->submit_button = new Button($page);
        $this->submit_button->classList->add("btn-success");
        $this->submit_button->setAttribute("type", "submit");
        $this->submit_button->setAttribute("icon", "fa fa-check");
        $this->submit_button->setAttribute("is", "alt-button");
        $this->submit_button->label("Submit");

        $this->box->footer->append($this->submit_button);

        $this->reset_button = new Button($page);
        $this->reset_button->classList->add("btn-info");
        $this->reset_button->icon("fa fa-rotate-left")->label("Reset");
        $this->reset_button->setAttribute("type", "reset");
        $this->box->footer->append($this->reset_button);

        $this->back_button = new Button($page);
        $this->back_button->classList->add("btn-warning");
        $this->back_button->label("Back");
        $this->back_button->setAttribute("type", "button");
        if ($_GET["fancybox"]) {
            $this->back_button->setAttribute("data-fancybox-close", true);
        } else {
            $this->back_button->setAttribute("onClick", 'javascript:history.back(-1)');
        }

        $this->box->footer->append($this->back_button);
        if ($_GET["fancybox"]) {
            $this->action($page->uri());
        }

        $this->appendChild($this->box);

        $this->show_back = true;
        $this->show_reset = false;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case "show_back":
                if ($value) {
                    $this->back_button->classList->remove("hide");
                } else {
                    $this->back_button->classList->add("hide");
                }
                return;
                break;
            case "show_reset":
                if ($value) {
                    $this->reset_button->classList->remove("hide");
                } else {
                    $this->reset_button->classList->add("hide");
                }
                return;
                break;
        }

        parent::__set($name, $value);
    }

    public function action($action = "")
    {
        $this->setAttribute("action", $action);
        return $this;
    }

    public function addBody($body)
    {

        $this->box->body()->append((string )$body);
        return $this;
    }

    public function submitCheck($func)
    {
        $this->submit_check = $func;
        return $this;
    }

    public function box()
    {
        return p($this->box);
    }

    public function addHidden($name, $value)
    {
        $input = p("input")->appendTo($this);
        $input->attr("name", $name)->attr("type", "hidden")->val($value);
        return $input;
    }

    public function attr($name,$value){
        return p($this)->attr($name,$value);
    }
}
