<?php

class DynamicPage_edit_data extends ALT\Page
{
    public function post()
    {
        $data = [];
        foreach ($_POST as $k => $v) {
            if (is_array($v)) {

                $arr = [];
                foreach ($v["c"] as $a) {
                    $arr[] = $a;
                }

                foreach ($v["u"] as $a) {
                    $arr[] = $a;
                }
                $data[$k] = $arr;
            } else {
                $data[$k] = $v;
            }
        }
        $obj = $this->object();
        $obj->data = $data;
        $obj->save();
        $this->redirect();
    }

    public function get()
    {
        $obj = $this->object();
        $path = $this->app->document_root . "/" . $obj->path;
        $code = file_get_contents($path);

        $ext = new Twig\Dynamic\Extension();

        $ret = $ext->parse($code);


        $e = $this->createE($this->object()->data ?? []);
        foreach ($ret as $t) {
            if ($t["type"] == "text") {
                $e->add($t["name"])->input($t["name"]);
                continue;
            }
            if ($t["type"] == "image") {
                $e->add($t["name"])->fileman($t["name"]);
                continue;
            }

            if ($t["type"] == "list") {
                $that = $this;
                $e->add($t["name"], function ($d) use ($t, $that) {
                    $table = $that->createT($d[$t["name"]]);
                    $table->formCreate(["name" => $t["name"]]);
                    foreach ($t["body"] as $c) {
                        $table->add($c["name"])->input($c["name"]);
                    }
                    return $table;
                });
            }
        }

        $this->write($this->createForm($e));
    }
}
