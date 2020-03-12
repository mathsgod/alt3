<?php

namespace Type;

use GraphQL\Error\Error;
use App\User;
use App\UI;
use Exception;

class Mutation
{
    public function sidebarNavAddClass($root, $args, $app)
    {
        //check style type
        $attr = User::__attribute("style");
        if ($attr["Type"] !== "json") {
            throw new Error("Table User, field style must be set to json");
        }

        $c = $app->user->style["sidebar_nav"] ?? [];
        $c[] = $args["class"];
        $app->user->style["sidebar_nav"] = array_values($c);
        $app->user->save();
        return true;
    }


    public function sidebarNavRemoveClass($root, $args, $app)
    {
        $attr = User::__attribute("style");
        if ($attr["Type"] !== "json") {
            throw new Error("Table User, field style must be set to json");
        }

        $c = $app->user->style["sidebar_nav"] ?? [];

        $app->user->style["sidebar_nav"] = array_filter($c, function ($c) use ($args) {
            return $c != $args["class"];
        });
        $app->user->save();
        return true;
    }

    public function bodyAddClass($root, $args, $app)
    {
        //check style type
        $attr = User::__attribute("style");
        if ($attr["Type"] !== "json") {
            throw new Error("Table User, field style must be set to json");
        }

        $body = $app->user->style["body"] ?? [];
        $body[] = $args["class"];
        $app->user->style["body"] = array_values($body);
        $app->user->save();
        return;
    }

    public function bodyRemoveClass($root, $args, $app)
    {
        $attr = User::__attribute("style");
        if ($attr["Type"] !== "json") {
            throw new Error("Table User, field style must be set to json");
        }

        $body = $app->user->style["body"] ?? [];

        $app->user->style["body"] = array_filter($body, function ($c) use ($args) {
            return $c != $args["class"];
        });
        $app->user->save();
    }

    public function sidebar($root, $args, $app)
    {
        $app->user->style["sidebar"] = $args["class"];
        $app->user->save();
        return true;
    }

    public function mainHeader($root, $args, $app)
    {
        $app->user->style["header"] = $args["class"];
        $app->user->save();
        return true;
    }

    public function loginWebAuthn($root, $args, $app)
    {
        try {
            $app->loginFido2($args["username"], $args["assertion"]);
            return true;
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
        return false;
    }

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
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
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
