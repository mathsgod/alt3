<?php

namespace App\GQL;

class Mutation
{
    public function __call($name, $arguments)
    {
        $root = $arguments[0];
        $args = $arguments[1];
        $app =  $arguments[2];
        if (starts_with($name, "update")) {
            $module = $app->module($name);
            $class = $module->class;
            $key = $class::_key();

            $obj = $class::Query([
                $key => $args[$key]
            ])->first();
            $obj->bind($args["data"]);
            return $obj->save();
        }
    }
}
