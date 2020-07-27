<?php

namespace  TWig\Dynamic\Image;

use Twig\TwigFilter;

class Filter extends TwigFilter
{
    public function __construct()
    {
        parent::__construct("image", [$this, "callback"]);
    }

    public function callback($value)
    {
        return $value;
    }
}
