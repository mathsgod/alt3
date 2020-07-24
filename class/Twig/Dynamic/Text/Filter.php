<?php

namespace  TWig\Dynamic\Text;

use Twig\TwigFilter;

class Filter extends TwigFilter
{
    public function __construct()
    {
        parent::__construct("text", [$this, "callback"]);
    }

    public function callback()
    {
        return "aaa";
    }
}
