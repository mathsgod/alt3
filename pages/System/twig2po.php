<?php

class System_twig2po extends ALT\Page {
    public function post() {
        outp($_POST);
    }
    public function get() {
        $s = new stdClass();
        $s->source = realpath(getcwd() . "/../pages");
    	$s->target = realpath(getcwd() . "/../pages/locale");

        $e = $this->createE($s);
        $e->add("Source")->input("source");
    	$e->add("Target")->input("target");

        $this->write($this->createForm($e));
    }
}

?>