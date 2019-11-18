<?
namespace App\UI;

use Closure;
use P\HTMLDivElement;
use App\Page;

class DataTables extends HTMLDivElement
{

    const ATTRIBUTES = [
        "searching" => [
            "type" => "json",
            "name" => "data-searching"
        ],
        "paging" => [
            "type" => "json",
            "name" => "data-paging"
        ],
        "responsive" => [
            "type" => "json",
            "name" => "data-responsive"
        ],
        "processing" => [
            "type" => "json",
            "name" => "data-responsive"
        ],
        "dom" => [
            "name" => "data-dom"
        ],
        "pageLength" => [
            "type"=>"string",
            "name" => "data-page-length"
        ]
    ] + parent::ATTRIBUTES;

    private $columns = [];
    private $objects = null;

    public $ajax = null;
    public $serverSide = false;
    public $ordering = true;
    public $scrollX = false;
    public $order = [];

    public $response = null;

    public $_order = [];
    public $select = true;
    public $autoWidth = true;


    //public $buttons = ['print', 'copy', 'excel', 'pdf'];
    public $buttons = [];

    public $fixedHeader = ["header" => false];

    public $page = null;

    public function __construct($objects, Page $page)
    {
        parent::__construct();
        $this->setAttribute("is", "alt-datatables");
        $this->searching = true;
        $this->responsive = true;
        $this->processing = true;

        $this->dom = "<'row'<'col-sm-12'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-7'p><'col-sm-5'i>><'row'<'col-sm-6'B><'col-sm-6'l>>";

        $this->objects = $objects;
        $this->page = $page;
        $this->response = new DTResponse($objects);
    }

    public function boxStyle()
    {
        $this->dom = "<'box no-border'" .
            "<'box-body no-padding'tr>" .
            "<'box-footer'<'row'<'col-sm-7'p><'col-sm-5'i>>" .
            "<'row'<'col-sm-6'B><'col-sm-6'l>>>" .
            ">";
        return $this;
    }

    public function order($data, $dir)
    {
        $this->_order[] = [$data, $dir];
        return $this;
    }

    public function addEdit()
    {
        $c = $this->response->addEdit();
        $this->columns[] = $c;
        return $c;
    }

    public function addView()
    {
        $c = $this->response->addView();
        $this->columns[] = $c;
        return $c;
    }

    public function addDel()
    {
        $c = $this->response->addDel();
        $this->columns[] = $c;
        return $c;
    }

    public function add($title, $getter)
    {
        $c = new Column();

        if ($this->page) {
            $c->title = $this->page->translate($title);
        } else {
            $c->title = $title;
        }

        $c->descriptor[] = $getter;


        if ($getter instanceof Closure) {
            $c->data = md5(new \ReflectionFunction($getter));
            $c->name = $c->data;
        } else {
            $c->data = $getter;
            $c->name = $getter;
        }

        $c->data = str_replace(["(", ")"], "_", $c->data);



        if (!$this->serverSide) {
            $c->orderable = true;
        }

        $this->columns[] = $c;


        return $c;
    }

    public function _data()
    {
        $data = [];
        foreach ($this->objects as $k => $obj) {
            $d = [];
            foreach ($this->columns as $col) {
                $d[$col->data] = (string)$col->getData($obj, $k);
            }
            $data[] = $d;
        }
        return $data;
    }


    public function __toString()
    {
        $this->setAttribute("data-columns", json_encode($this->columns));

        /* $this->attributes["data-searching"] = $this->searching ? "true" : "false";
        $this->attributes["data-paging"] = $this->paging ? "true" : "false";
        $this->attributes["data-responsive"] = $this->responsive ? "true" : "false";
        $this->attributes["data-processing"] = $this->processing ? "true" : "false";
        $this->attributes["data-scroll-x"] = $this->scrollX ? "true" : "false";
        $this->attributes["data-dom"] = $this->dom;
        $this->attributes[":buttons"] = $this->buttons;
        $this->attributes["data-select"] = $this->select ? "true" : "false";

        $this->attributes["data-page-length"] = $this->pageLength;

        $this->attributes["data-auto-width"] = $this->autoWidth ? "true" : "false";

        if ($this->fixedHeader) {
            $this->attributes["data-fixed-header"] = $this->fixedHeader;
        }

        $order = [];
        foreach ($this->_order as $o) {
            foreach ($this->columns as $i => $c) {
                if ($c->data == $o[0]) {
                    $order[] = [$i, $o[1]];
                    continue;
                }
            }
        }

        $this->attributes["data-order"] = $order;
*/
        if ($this->ajax) {
            if ($this->serverSide) {

                $cs = [];
                foreach ($this->columns as $c) {
                    $cs[] = ["searchType" => $c->searchType];
                }
                $this->ajax["data"]["_columns"] = $cs;

                $this->attributes["data-server-side"] = "true";
            }
            $this->attributes["data-ajax"] = $this->ajax;
        } else {
            $this->setAttribute("data-data", json_encode($this->_data()));
        }




        return parent::__toString();
    }
}
