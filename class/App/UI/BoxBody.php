<?php

namespace App\UI;

use P\HTMLDivElement;
use App\Page;

class BoxBody extends HTMLDivElement
{
    public $page;
    public function __construct(Page $page)
    {
        parent::__construct();
        $this->page = $page;

        $this->setAttribute("is", "alt-box-body");
        $this->classList[] = "box-body";
    }
}
