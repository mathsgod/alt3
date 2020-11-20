<?php

namespace App\UI;

use P\HTMLElement;

class RTableColumn extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("r-table-column");
    }

    public function editable(string $type = "text")
    {
        $this->setAttribute("editable", true);
        $this->setAttribute("edit-type", $type);
        return $this;
    }

    public function ss()
    {
        $this->sortable();

        $this->searchable();
        return $this;
    }

    public function searchable(string $type = "text")
    {
        $this->setAttribute("searchable", true);
        $this->setAttribute("search-type", $type);
        return $this;
    }

    public function searchOption($options = null)
    {
        $opt = [];
        foreach ($options as $value => $label) {
            $opt[] = [
                "value" => $value,
                "label" => $label
            ];
        }
        $this->setAttribute(":search-option", json_encode($opt, JSON_UNESCAPED_UNICODE));
        return $this;
    }

    public function sortable()
    {
        $this->setAttribute("sortable", true);
        return $this;
    }
}
