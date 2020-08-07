<?php

namespace Vue;

class JSCode
{
    public $code;
    public function code(string $code)
    {
        $this->code = $code;
    }
}

function js(string $code)
{
    return new JSCode($code);
}
