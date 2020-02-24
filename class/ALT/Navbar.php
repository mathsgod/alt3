<?php

namespace ALT;

class Navbar extends \P\HTMLElement
{
    protected $page;
    public function __construct(Page $page)
    {
        $this->page = $page;
        parent::__construct("nav");
        $this->classList->add("navbar navbar-expand  navbar-light");

        $button = p("button")->attr("type", "button")->addClass("navbar-toggler")->appendTo($this);
        $button->attr("data-toggle", "collapse");
        $button->attr("data-target", "#_navbar_collapse");
        $button->html('<span class="navbar-toggler-icon"></span>');

        $this->_content = p("div")->appendTo($this);
        $this->_content->addClass("collapse navbar-collapse");
        $this->_content->attr("id", "_navbar_collapse");
    }

    public function hasButton(): bool
    {
        return $this->_content[0]->hasChildNodes();
    }

    public function showEdit()
    {
        $obj = $this->page->object();
        if ($obj->canUpdate()) {
            $a = new \App\UI\A();
            $a->setAttribute("href", $obj->uri("ae"));
            $a->classList->add("btn-warning btn-sm m1 navbar-btn text-white");
            $a->innerHTML = "<i class='fa fa-pencil-alt fa-fw'></i>";
            $this->_content->prepend($a);
        }
    }

    public function showDelete()
    {
        $obj = $this->page->object();
        if ($obj->canDelete()) {
            $a = new \App\UI\A();
            $a->setAttribute("href", $obj->uri("del"));
            $a->classList->add("btn-danger btn-sm m1 navbar-btn confirm");
            $a->innerHTML = "<i class='fa fa-times fa-fw'></i>";
            $this->_content->append($a);
        }
    }

    public function addButton(string $label, $uri = null): \App\UI\A
    {
        $a = new \App\UI\A();
        $a->classList->add("btn-info");
        $a->classList->add("btn-sm");
        $a->classList->add("m-1");
        $a->classList->add("navbar-btn");

        $this->_content->append($a);

        if ($label) {
            $label = $this->page->translate($label);
        }
        $a->textContent = $label;

        $a->setAttribute("href", $uri);

        return $a;
    }

    public function addButtonGroup(): ButtonGroup
    {
        $bg = new ButtonGroup($this->page);
        $this->_content->append($bg);
        return $bg;
    }

    public function addDropdown(string $label = null)
    {
        $dd = new Dropdown($label);
        $this->_content->append($dd);
        return $dd;
    }

    public function __toString()
    {

        $this->_content->find(".btn-group")->find(".btn")->each(function ($i, $o) {
            $o->classList[] = "btn-sm";
            $o->classList[] = "navbar-btn";
        });

        return parent::__toString();
    }
}
