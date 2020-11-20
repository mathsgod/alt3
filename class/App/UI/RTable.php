<?php


namespace App\UI;

use Closure;
use P\HTMLElement;


class RTable extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("r-table");
    }

    public function setCellUrl(string $url)
    {
        $this->setAttribute("cell-url", $url);
        return $this;
    }

    public function setKey(string $key)
    {
        $this->setAttribute("key", $key);
        return $this;
    }

    public function add(string $label, string $prop)
    {
        $col = new RTableColumn();
        $col->setAttribute("label", $label);
        $col->setAttribute("prop", $prop);


        $this->append($col);

        return $col;
    }

}
