<?php
namespace My;
class V extends Query {
    private $_split = 0;
    private $_break = [];
    public $columns = [];
    public $tableClass = [];

    public function __construct($object) {
        parent::__construct("div");
        $this->addClass("clearfix row");
        $this->object = $object;
        $this->_break[$this->_split] = 1;
        // if (\API::User()->setting("table-condensed")) {
        $this->tableClass = "table-condensed";
        // }
    }

    public function addHr() {
        $this->columns[] = "<!--__HR__-->";
    }

    public function addSplit() {
        $this->_break[$this->_split]++;
        $this->columns[] = "<!--__SPLIT__-->";
    }

    public function addBreak() {
        $this->_split++;
        $this->_break[$this->_split] = 1;
        $this->columns[] = "<!--__BREAK__-->";
    }

    public function addNext() {
        $this->columns[] = "<!--__NEXT__-->";
    }

    public function add($label, $getter = null) {

        $tr = p("tr");
        $td = p("th")->append($label)->appendTo($tr);
        $td->addClass("bg-primary");
        $td->addClass("col-xs-2 col-md-2 col-lg-2");

        $c = new C("td");
        
        if ($getter) {
            $c->attr("index", $getter);
        }

        $this->columns[] = [$tr, $c];

        return $c;
    }

    public function __toString() {
        $this->empty();

        $div = p("div")->appendTo($this);
        $break = 0;
        $col = floor(12 / $this->_break[$break]);
        $div->addClass("col-md-{$col}");

        $panel = p("div")->addClass("panel panel-default")->appendTo($div);
        $table = p("table")->addClass("table")->appendTo($panel);
        $table->addClass($this->tableClass);
        $tbody = p("tbody")->appendTo($table);

        foreach($this->columns as $col) {
            if ($col == "<!--__BREAK__-->") {
                $div = p("div")->addClass("clearfix")->appendTo($this);
                $break++;
                $col = floor(12 / $this->_break[$break]);
                $div->addClass("col-md-{$col}");

                $panel = p("div")->addClass("panel panel-default")->appendTo($div);
                $table = p("table")->addClass("table")->appendTo($panel);
                $table->addClass($this->tableClass);
                $tbody = p("tbody")->appendTo($table);
            } elseif ($col == "<!--__NEXT__-->") {
                $panel = p("div")->addClass("panel panel-default")->appendTo($div);
                $table = p("table")->addClass("table")->appendTo($panel);
                $table->addClass($this->tableClass);
                $tbody = p("tbody")->appendTo($table);
            } elseif ($col == "<!--__HR__-->") {
                $div->append("<hr/>");

                $panel = p("div")->addClass("panel panel-default")->appendTo($div);
                $table = p("table")->addClass("table")->appendTo($panel);
                $table->addClass($this->tableClass);
                $tbody = p("tbody")->appendTo($table);
            } elseif ($col == "<!--__SPLIT__-->") {
                $col = floor(12 / $this->_break[$break]);
                $div = p("div")->addClass("col-md-{$col}")->appendTo($this);

                $panel = p("div")->addClass("panel panel-default")->appendTo($div);
                $table = p("table")->addClass("table")->appendTo($panel);
                $table->addClass($this->tableClass);
                $tbody = p("tbody")->appendTo($table);
            } else {
                $col[0]->append($col[1]);
                $tbody->append($col[0]);
            }
        }

        $this->binding($this->object);
        // check format
        $this->find("td")->each(function($i, $o) {
                $format = p($o)->attr('format');
                if (!$format)return;
                $result = Func::_($format)->call(p($o)->html());
                p($o)->html($result);
            }
            );

        return parent::__toString();
    }
}

?>