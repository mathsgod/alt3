<?php
use App\Translate;

class Translate_list extends App\Page
{
    public function get()
    {

        $jq =$this->createRT([$this,"ds"]);

        $jq->order("module", "desc");

        $jq->addEdit();
        $jq->addDel();
        $jq->add("Module", "module")->sort()->SearchOption($this->app->getModule(), "name", "name");
        $jq->add("Action", "action")->sort()->search();
        $jq->add("Name", "name")->sort()->search();


        foreach ($this->app->config["language"] as $v => $l) {
            $jq->add($l, function ($obj) use ($v, $l) {
                    $w[] = "language='$v'";
                    $w[] = "name='$obj->name'";
                    $w[] = $obj->module?"(module='$obj->module')":"(module is null)";
                    $w[] = $obj->action?"(action = '$obj->action')":"(action is null)";
                    return Translate::first($w)->value;
            }
                )->index($v);
        }

        $this->write($jq);
    }

    public function ds($rt)
    {
        $w=$rt->where();
        $lang=array_keys($this->app->config["language"]);

        $w[]=["language=?",$lang[0]];
        $data["total"]=App\Translate::Count($w);
        $data["data"]=App\Translate::Find($w, $rt->order(), $rt->limit());

        return $data;
    }
}
