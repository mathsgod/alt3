<?php

class Translate_ae extends ALT\Page
{
    public function post()
    {
        $obj = $this->object();
        if ($obj->translate_id) {
            $obj->delete();
        }
        // create
        foreach ($this->app->config["language"] as $k => $v) {
            $t = new App\Translate();
            $t->module = $_POST["module"];
            $t->action = $_POST["action"];
            $t->name = $_POST["name"];
            $t->value = $_POST[$k];
            $t->language = $k;
            $t->save();
        }
        App::Redirect();
    }

    public function get()
    {
        $obj = $this->object();

        $e = My::E($obj);
        $e->add("Module")->input("module");
        $e->add("Action")->input("action");
        $e->add("Name")->input("name")->required();

        $e->addHr();
        foreach ($this->app->config["language"] as $k => $v) {
            $e->add($v)->input()->attr("name", $k)->val((string)$obj->get($k));
        }

        $this->write($this->createForm($e)->action(''));
    }
}
