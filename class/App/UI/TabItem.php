<?php
namespace App\UI;

class TabItem
{
    public $li;
    public $div;

    public function __construct()
    {
        $this->li = p("li")[0];
        $this->div = p("div")[0];
        $this->div->classList[]="tab-pane";
    }

    public function active()
    {
        $this->li->classList[]='active';
        $this->div->classList[]='active';
    }

    public function addBadge($text)
    {
        // <span data-toggle="tooltip" title="abc" class="badge bg-yellow" data-original-title="3 New Messages" aria-describedby="tooltip357532">3</span>
        $span = p("span");
        $span->addClass("badge");

        $span->text($text);

        p($this->li)->find("a")->append(" ");
        p($this->li)->find("a")->append($span);
        return $span;
    }
}