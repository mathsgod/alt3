<?php
namespace Type;

class Me
{
    public function group($user, $args, $context)
    {
        return $user->UserGroup();
    }

    public function credentialCreationOptions($user, $args, $context)
    {
        $weba = new \R\WebAuthn($_SERVER['HTTP_HOST']);
        return $weba->prepare_challenge_for_registration($user->username, (string)$user->user_id);
    }

    public function registrationWebAuthn($user, $args, $context)
    {
        $weba = new \R\WebAuthn($_SERVER['HTTP_HOST']);
        $user->credential = json_encode($weba->register(json_decode($args["attestion"])));
        $user->save();
        return true;
    }
}