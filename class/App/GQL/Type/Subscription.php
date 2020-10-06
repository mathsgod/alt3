<?php

namespace App\GQL\Type;


use Exception;
use GraphQL\Error\Error;

class Subscription
{
    public function __call($name, $arguments)
    {
        $root = $arguments[0];
        $args = $arguments[1];
        $app =  $arguments[2];

        if (starts_with($name, "create")) {

            $module = $app->module(substr($name, 6));
            $class = $module->class;
            $obj = new $class();
            $obj->bind($args);
            try {
                $obj->save();
            } catch (Exception $e) {
                throw new Error($e->getMessage());
            }
            return $obj->id();
        }
    }
}
