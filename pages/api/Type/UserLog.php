<?php

namespace Type;

class UserLog
{
    public function login_time($o)
    {
        return $o->login_dt;
    }
    
    public function logout_time($o)
    {
        return $o->logout_dt;
    }
}
