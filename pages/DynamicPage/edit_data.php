<?php

class DynamicPage_edit_data extends ALT\Page
{
    public function post()
    {

        $data = $this->request->getParsedBody();

        $obj = $this->object();
        $obj->data = $data;

        $obj->save();

        return ["data" => true];
    }

    public function structure()
    {

        $obj = $this->object();
        $path = $this->app->document_root . "/" . $obj->path;
        $code = file_get_contents($path);

        $ext = new Twig\Dynamic\Extension();

        $ret = $ext->parse($code);

        return $ret;
    }
    public function data()
    {
        return $this->object()->data;
    }

    public function get()
    {
        return;
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
                        switch ($c["type"]) {
                            case "text":
                                $table->add($c["name"])->input($c["name"]);
                                break;
                            case "image":
                                $table->add($c["name"])->fileman($c["name"]);
                                break;
                        }
                    }
                    return $table;
                });
            }
        }

        $this->write($this->createForm($e));
    }
}
