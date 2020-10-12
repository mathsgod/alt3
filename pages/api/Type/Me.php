<?php

namespace Type;

use App\UserLog;
use App\EventLog;
use Google\Authenticator\GoogleAuthenticator;
use Endroid\QrCode\QrCode;

class Me
{
    public function hasTwoStepVerification($user, $args, $app): bool
    {
        return (bool)$user->secret;
    }

    public function group($user, $args, $context)
    {
        return $user->UserGroup();
    }

    public function credentialCreationOptions($user, $args, $context)
    {
        $weba = new \R\WebAuthn($_SERVER['HTTP_HOST']);
        return $weba->prepare_challenge_for_registration($user->username, (string)$user->user_id);
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

    public function twoStepVerification($user, $args, $app)
    {
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();



        $host = $_SERVER["HTTP_HOST"];

        $url = sprintf("otpauth://totp/%s@%s?secret=%s", $user->username, $host, $secret);

        $qrCode = new QrCode($url);

        return [
            "secret" => $secret,
            "host" => $host,
            "image" =>  $qrCode->writeDataUri()
        ];
    }
}
