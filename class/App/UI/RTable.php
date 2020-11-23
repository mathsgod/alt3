<?php


namespace App\UI;

use Closure;
use P\HTMLElement;


class RTable extends HTMLElement
{
    public function __construct()
    {
        parent::__construct("r-table");

        $this->dropdown = new Element("template");
        $this->dropdown->setAttribute("slot", "dropdown");
        $this->append($this->dropdown);
    }

    public function addView()
    {
        $col = new RTableColumn();
        $col->setAttribute("prop", "__view__");
        
        $this->append($col);

        return $col;
    }

    public function addEdit()
    {
        $col = new RTableColumn();
        $col->setAttribute("prop", "__edit__");
        
        $this->append($col);
        return $col;
    }

    public function addDel()
    {
        $col = new RTableColumn();
        $col->setAttribute("prop", "__del__");
        
        $this->append($col);
        return $col;
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

    public function selectable()
    {
        $this->setAttribute("selectable", true);
    }

    public function add(string $label, string $prop)
    {
        $col = new RTableColumn();
        $col->setAttribute("label", $label);
        $col->setAttribute("prop", $prop);


        $this->append($col);

        return $col;
    }

    /**
     * @param array|string $url
     */
    public function addDropdown(string $label, $url, array $param = [])
    {

        if (is_array($url)) {
            $url = (string) $url[0]->path() . "/" . $url[1] . "?" . http_build_query($param);
        }

        $item = new Element("r-table-dropdown-item");
        $item->setAttribute("label", $label);
        $item->setAttribute("url", $url);


        $this->dropdown->append($item);
    }
}
