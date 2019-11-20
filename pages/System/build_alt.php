<?php

class System_build_alt extends ALT\Page {
    public function get() {
        App\Plugin::Load("less.php");

        $parser = new Less_Parser();
        $parser->parseFile(getcwd() . "/AdminLTE-2.3.7/build/less/AdminLTE.less", '../css/');
        $css = $parser->getCss();
        file_put_contents(getcwd() . "/data/AdminLTE.css", $css);
    	$this->write('done');
    }
}

?>