<?php
namespace My;
class Grid extends \BS\Grid {
    public $row;
    public function __construct() {
        parent::__construct();
        $this->row();
    }

    public function col($size, $contents) {
        $col = $this->row->addCol($size);
        $col->append($contents);
        return $col;
    }

    public function row() {
        $this->row = $this->addRow();
        return $this;
    }
}

?>