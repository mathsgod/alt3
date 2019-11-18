<?php
namespace App\UI;

class T extends Box
{
    public $objects;
    public $table;

    public function __construct($objects, \App\Page $route)
    {
        parent::__construct($route);
        $this->objects = $objects;
        $this->body->classList->add("no-padding");
        $this->body->classList->add('table-responsive');
        $this->classList->add("box-primary");
        $this->table = new Table($objects, $route);
        $this->table->classList->add("table-condensed");
        $this->table->classList->add("table-hover");
        $this->body->append($this->table);
    }

    public function addCheckbox($index, $callback)
    {
        return $this->table->addCheckbox($index, $callback);
    }

    public function add($label, $getter = null)
    {
        return $this->table->add($label, $getter);
    }

    public function addChildRow($label, $getter = null)
    {
        return $this->table->addChildRow($label, $getter);
    }

    public function addView()
    {
        return $this->table->addView();
    }

    public function addEdit()
    {
        return $this->table->addEdit();
    }

    public function addDel()
    {
        return $this->table->addDel();
    }

    public function setCreate(string $uri)
    {
        return $this->header->addButton(null,$uri)->icon("fa fa-fw fa-plus")->addClass("btn-primary");
    }

    public function formCreate($options, $default = null)
    {
        $this->table->setAttribute("form-create", true);

        if (is_string($options)) {
            $opt = [
                "name" => $options,
                "default" => $default
            ];
        } else {
            $opt = $options;
        }

        $this->table->setAttribute("form-name", $opt["name"]);
        $this->table->default = $opt["default"];

        return $this;
    }

    public function subHTML($label, $callback, $index)
    {
        $url = $callback[0]->path() . "/" . $callback[1];
        return $this->table->add($label, function ($o) use ($url, $index) {
            if (is_object($o)) {
                if ($index) {
                    $url .= "?" . http_build_query([$index => $o->$index]);
                } else {
                    $url .= "?" . http_build_query(["id" => $o->ID()]);
                }
            } else {
                $url .= "?" . http_build_query([$index => $o[$index]]);
            }

            return "<button class='btn btn-xs btn-primary table-childrow-btn table-childrow-close' data-url='$url' data-target=''><i class='fa fa-chevron-up'></i></button>";
        });
    }
}
