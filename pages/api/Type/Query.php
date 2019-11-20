<?
namespace Type;

use Exception;
use GraphQL\Error\Error;

class Query
{

    public function me($root, $args, $context)
    {
        if ($context->user->user_id == 2) return null;
        return $context->user;
    }

    public function forgotPassword($root, $args, $context)
    {
        $w[] = ["username=?", $args["username"]];
        $w[] = ["email=?", $args["email"]];
        $w[] = "status=0";
        if ($user = \App\User::first($w)) {
            try {
                $user->sendPassword();
            } catch (Exception $e) {
                throw new Error($e->getMessage());
            }
        }
        return true;
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

    public function loginWebAuthn($root, $args, $context)
    {
        try {
            $context->loginFido2($args["username"], $args["assertion"]);
            return true;
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
        return false;
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
