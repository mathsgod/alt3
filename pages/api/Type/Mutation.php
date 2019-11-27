<?php

namespace Type;

use GraphQL\Error\Error;
use App\User;
use App\UI;

class Mutation
{
    public function addFavorite($root, $args, $app): bool
    {
        $ui = new UI();
        $ui->user_id = $app->user->user_id;
        $ui->uri = 'fav';
        $ui->layout = json_encode($args);
        $ui->save();
        return true;
    }

    public function updateFavoriteSequence($root, $args, $app): bool
    {
        foreach ($args["id"] as $i => $id) {
            $ui = new \App\UI($id);
            $ui->sequence = $i;
            $ui->save();
        }
        return true;
    }

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
