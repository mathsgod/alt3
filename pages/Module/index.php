<?php

use App\Module;

class Module_index extends ALT\Page
{
    public function get($page)
    {
        $box = $this->createBox();
        p($box)->addClass('box-solid');
        $box->header("Module")->addClass('with-border');
        $box->body()->addClass('no-padding');

        $nav = new BS\Nav();
        $nav->addClass("nav-pills nav-stacked");

        foreach ($this->app->getModule() as $module) {
            $li = $nav->add($module->name, "Module?page={$module->class}");
            $li->find("a")->prepend("<i class='{$module->icon()}'></i> ");

            if ($page == $module->class) {
                $li->addClass("active");
            }
        }

        $box->body()->append($nav);

        $tpl = $this;
        $tpl->assign("left_box", $box);

        if ($page) {
            $m = Module::_($page);

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
                    
                    $btn=html("a")->class("btn btn-xs btn-success");
                    $btn->i->class('fa fa-plus fa-fw');
                    $btn->text(" Column");
                    $table_name=$table->name;
                    $btn->href("Module/add_column?table={$table_name}");

                    $t1[]=$btn;
                    

                    $t = $this->createT($class::__db()->table($table)->columns());
                    $t->add("", function ($o) {
                        $btn=html("a")->class("btn btn-xs btn-danger");
                        $btn->i->class('fa fa-times fa-fw');
                        $table_name=$o->table()->name;
                        $btn->href("Module/del_column?table={$table_name}&field={$o->Field}");
                        
                        return $btn;
                    });
                    $t->add("", function ($o) {
                        $btn=html("a")->class("btn btn-xs btn-warning");
                        $btn->i->class('fa fa-pencil-alt fa-fw');
                        $table_name=$o->table()->name;
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
            } catch (exception $e) {
            }
            $tpl->assign("content", implode("", $t1));
        }
    }
}
