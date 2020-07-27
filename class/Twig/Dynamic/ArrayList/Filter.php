<?php

namespace  TWig\Dynamic\ArrayList;

use Twig\TwigFilter;

class Filter extends TwigFilter
{
    public function __construct()
    {
        parent::__construct("list", [$this, "callback"]);
    }

    public function callback($value)
    {
        return $value;
    }
}
