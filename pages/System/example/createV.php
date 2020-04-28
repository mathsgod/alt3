<?php
class System_example_createV extends ALT\Page
{
    public function get()
    {
        $v = $this->createV($this->app->user);
        $v->add("Username", "username");
        $v->addBreak();
        $v->add("Username", "username");
        $this->write($v);

        $v = $this->createV($this->app->user);
        $v->add("Username", "username");

        $v->add("Gateway", function ($o) {
            return nl2br(str_replace(' ', '&nbsp;', "1 2 3"));
        })->cell()->css("font-family", "consolas");


        $v->add("Alink", function ($o) {
            return $o;
        })->alink("v");;


        $this->write($v);
    }
}
