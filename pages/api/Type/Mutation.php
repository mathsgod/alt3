<?php
namespace Type;

use \GraphQL\Error\Error;

class Mutation
{
    public function login($root, $args, $context)
    {
        try {
            $context->login($args["username"], $args["password"], $args["code"]);
            return true;
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
}
