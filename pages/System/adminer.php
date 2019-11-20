<?php

class System_adminer extends ALT\Page
{
    public function get()
    {
        $plugins=$this->addLib("adminer");

        if ($plugins) {
            $this->write("<a class='btn btn-primary'' href='$plugins->base' target='_blank'>Adminer</a>");
        }
    }
}
