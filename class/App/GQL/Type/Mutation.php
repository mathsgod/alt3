<?php

namespace App\GQL\Type;

use GraphQL\Error\Error;
use App\User;
use App\UserGroup;
use App\UserList;

class Mutation
{
    public function updateUserGroup($root, $args, $app)
    {
        //only admin or power user can update
        if (!$app->me->isAdmin() && !$app->me->isPowerUser()) {
            throw new Error("access deny");
        }
        $usergroup = new UserGroup($args["usergroup_id"]);
        $usergroup->bind($args["data"]);
        return $usergroup->save();
    }


    public function updateUser($root, $args, $app)
    {
        //only admin or power user can update
        if (!$app->me->isAdmin() && !$app->me->isPowerUser()) {
            throw new Error("access deny");
        }
        $user = new User($args["user_id"]);
        $user->bind($args["data"]);
        if ($args["password"]) {
            $user->password = password_hash($args["password"], PASSWORD_DEFAULT);
        }

        return $user->save();
    }


    public function __call($name, $arguments)
    {
        $root = $arguments[0];
        $args = $arguments[1];
        $app =  $arguments[2];

        if (starts_with($name, "update")) {
            $module = $app->module(substr($name, 6));

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
