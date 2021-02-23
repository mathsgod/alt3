<?php

namespace App\GQL\Type;

use App\User;
use App\UserList;
use App\UserGroup;
use Exception;
use GraphQL\Error\Error;

class Subscription
{
    public function createUserGroup($root, $args, $app)
    {
        //only admin or power user can create
        if (!$app->me->isAdmin() && !$app->me->isPowerUser()) {
            throw new Error("access deny");
        }

        $usergroup = new UserGroup();
        $usergroup->bind($args);
        try {
            $usergroup->save();
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }


        //$app->alert->info("User group added");
        return $usergroup->usergroup_id;
    }

    public function createUser($root, $args, $app)
    {
        //only admin or power user can create
        if (!$app->me->isAdmin() && !$app->me->isPowerUser()) {
            throw new Error("access deny");
        }

        //check user duplication
        if (User::Query(["username" => $args["username"]])->count()) {
            throw new Error("user " . $args["usrname"] . " already exists");
        }


        $user = new User();
        $user->bind($args);
        $user->password = password_hash($args["password"], PASSWORD_DEFAULT);
        $user->save();

        foreach ($args["usergroup_id"] as $usergroup_id) {

            if ($usergroup_id == 1 && !$app->me->isAdmin()) { //only admin can add admin
                continue;
            }

            $o = new UserList();
            $o->usergroup_id = $usergroup_id;
            $o->user_id = $user->user_id;
            $o->save();
        }

        return $user->user_id;
    }

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
