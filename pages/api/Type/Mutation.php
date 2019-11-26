<?php

namespace Type;

use GraphQL\Error\Error;
use App\User;

class Mutation
{
    public function resetPassword($root, $args, $app): bool
    {
        $user = $app->user;
        $user->password = password_hash($args["new_password"], PASSWORD_DEFAULT);
        $user->save();
        return true;
    }

    public function updateMyInfo($root, $args, $app)
    {
        $user = $app->user;
        $user->bind($args);
        $user->save();
        return $app;
    }

    public function login($root, $args, $app)
    {
        try {
            $app->login($args["username"], $args["password"], $args["code"]);
            return $app->user;
        } catch (\Exception $e) {
            throw new Error($e->getMessage());
        }
    }

    public function me($root, $args, $context)
    {
        if ($context->user->user_id == 2) return null;
        return $context->user;
    }

    public function UserGroup($root, $args, $context)
    {

        return new \App\UserGroup($args["usergroup_id"]);
    }

    public function forgotPassword($root, $args, $app): bool
    {
        $user = User::Query([
            "username" => $args["username"],
            "email" => $args["email"],
            "status" => 0
        ])->first();

        if ($user) {
            try {
                $user->sendPassword($app);
            } catch (Exception $e) {
                throw new Error($e->getMessage());
            }
        }
        return true;
    }
}
