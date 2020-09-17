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

        $this->addLib("ckeditor/ckeditor");
        $e = $this->createE(["tinymce1" => "Abce", "ckeditor1" => "xyz"]);
        $e->add("tinymce")->tinymce("tinymce1");
        $e->add("ckeditor")->ckeditor("ckeditor1");

        $this->write($this->createForm($e));
    }
}
