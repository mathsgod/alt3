<?php
namespace Type;

use Exception;
use GraphQL\Error\Error;

class Query
{

    public function test(){
        return "testing";
    }
    
    public function me($root, $args, $context)
    {
        if ($context->user->user_id == 2) return null;
        return $context->user;
    }

 
    public function credentialRequestOptions($root, $args, $context)
    {
        if (!$user = \App\User::_($args["username"])) {
            throw new Error("user not found");
        }

        $credential = $user->credential;
        $weba = new \R\WebAuthn($_SERVER["HTTP_HOST"]);

        return $weba->prepare_for_login($credential);
    }

    public function UserGroup($root, $args, $context)
    {

        return new \App\UserGroup($args["usergroup_id"]);
    }
    public function UserGroups($root, $args, $context)
    {

        return \App\UserGroup::Query();
    }
}
