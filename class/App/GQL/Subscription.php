<?php

namespace App\GQL;

class Subscription
{
    public function __call($name, $arguments)
    {
        $root = $arguments[0];
        $args = $arguments[1];
        $app =  $arguments[2];

        if (starts_with($name, "create")) {

            $module = $app->module($name);
            $class = $module->class;
            $obj = new $class();
            $obj->bind($args);
            $obj->save();
            return $obj->id();
        }

    }
}
