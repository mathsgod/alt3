<?php
class System_composer extends ALT\Page
{
    public function get()
    {
        $this->callout->warning("Composer", "Install package through apache are not supported, please use ssh to install package.");

        $t = $this->createT($this->app->composer()->installed());
        $t->header->title = "Installed packages";

        $t->add("Name", "name");
        $t->add("Description", "description");
        $t->add("Version", "version");

        $this->write($t);
    }
}
