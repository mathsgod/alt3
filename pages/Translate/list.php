<?php

use App\Translate;

class Translate_list extends App\Page
{
    public function get()
    {

        $jq = $this->createRT2([$this, "ds"]);

        $jq->order("module", "desc");

        $jq->addEdit();
        $jq->addDel();
        $jq->add("Module", "module")->sort()->searchOption($this->app->modules(), "name", "name");
        $jq->add("Action", "action")->ss();
        $jq->add("Name", "name")->ss();

        foreach ($this->app->config["system"]["language"] as $v => $l) {
            $jq->add($l, "$l");
        }

        $this->write($jq);
    }

    public function ds($rt)
    {

        $lang = array_keys($this->app->config["system"]["language"]);

        $rt->source = App\Translate::Query([
            "language" => $lang[0]
        ]);


        foreach ($this->app->config["language"] as $v => $l) {
            $rt->add($l, function ($o) use ($v) {
                $w = [];
                $w[] = "language='$v'";
                $w[] = "name='$o->name'";
                $w[] = $o->module ? "(module='$o->module')" : "(module is null)";
                $w[] = $o->action ? "(action = '$o->action')" : "(action is null)";
                return Translate::first($w)->value;
            });
        }

        return $rt;
    }
}
