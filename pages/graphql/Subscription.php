<?php

namespace Type;

class Subscription extends \App\GQL\Subscription
{
    public function createUser($root, $args, $app)
    {
        return true;
    }
}
