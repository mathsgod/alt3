<?php

namespace Type;

use App\UserLog;
use App\EventLog;

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

    public function UserLog($user, $args, $app)
    {
        return UserLog::Query([
            "user_id" => $user->user_id
        ])->limit($args["limit"])->orderBy(["userlog_id" => "desc"]);
    }

    public function EventLog($user, $args, $app)
    {
        return EventLog::Query([
            "user_id" => $user->user_id
        ])->limit($args["limit"])->orderBy(["eventlog_id" => "desc"]);
    }
}
