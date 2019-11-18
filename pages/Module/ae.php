<?php

class Module_ae extends ALT\Page {
    public function get() {
        $e = $this->createE();
        $e->add("Name")->input("name")->required();

        $e->add("Log")->checkbox("log");
        $this->write($this->createForm($e));
    }
}