<?php
class System_example_tinymce extends ALT\Page
{
    public function post()
    {
        outP($_POST);
        outp($_FILES);
        die();
    }

    public function get()
    {

        $e = $this->createE(["tinymce1" => "Abce"]);
        $e->add("tinymce")->tinymce("tinymce1");

        $this->write($this->createForm($e));
    }
}
