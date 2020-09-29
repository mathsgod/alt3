<?php
class System_composer extends ALT\Page
{
    public function get()
    {
        $t = $this->createT($this->app->composer()->installed());
        $t->header->title = "Installed packages";

        $t->add("Name", "name");
        $t->add("Description", "description");
        $t->add("Version", "version");

        $this->write($t);
    }
}
