<?php

use App\Module;

class Module_index extends ALT\Page
{
    public function get($page = null)
    {
        $card = $this->createCard();
        $card->header->title = "Module";

        $card->body->classList->add("p-0");
        $ul = html("ul");
        $ul->class("nav nav-pills flex-column");

        foreach ($this->app->modules() as $module) {
            $li = $ul->li;
            $li->class("nav-item");
            $a = $li->a;
            $a->i->class($module->icon);
            $a->class("nav-link");
            $a->text(" " . $module->name);
            $a->href("Module?page=" . $module->name);


            if ($page == $module->name) {
                $li->class("nav-item active");
            }
        }

        $card->body->innerHTML = $ul;

        $this->data["left_box"] = $card;


        if ($page) {
            $m = $this->app->module($page);

            $e = $this->createV($m);
            $e->header("Details");
            foreach ($m as $k => $v) {
                $e->add($k, $k);
            }

            $t1[] = $e;

            $class = $m->class;
            $r = new ReflectionClass($class);

            try {
                if ($r->getMethod("_table")) {
                    $table = $class::_table();

                    $btn = html("a")->class("btn btn-xs btn-success");
                    $btn->i->class('fa fa-plus fa-fw');
                    $btn->text(" Column");
                    $table_name = $table->name;
                    $btn->href("Module/add_column?table={$table_name}");

                    $t1[] = $btn;


                    $t = $this->createT($class::__db()->table($table)->columns());
                    $t->add("", function ($o) {
                        $btn = html("a")->class("btn btn-xs btn-danger");
                        $btn->i->class('fa fa-times fa-fw');
                        $table_name = $o->table()->name;
                        $btn->href("Module/del_column?table={$table_name}&field={$o->Field}");

                        return $btn;
                    });
                    $t->add("", function ($o) {
                        $btn = html("a")->class("btn btn-xs btn-warning");
                        $btn->i->class('fa fa-pencil-alt fa-fw');
                        $table_name = $o->table()->name;
                        $btn->href("Module/alter_column?table={$table_name}&field={$o->Field}");

                        return $btn;
                    });
                    $t->add("Field", "Field");
                    $t->add("Type", "Type");
                    $t->add("Null", "Null");
                    $t->add("Key", "Key");
                    $t->add("Default", "Default");
                    $t->add("Extra", "Extra");

                    $t1[] = $t;
                }
            } catch (exception $e) { }
            $this->data["content"] = implode("", $t1);
        }
    }
}
