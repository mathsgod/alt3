<?php
class System_example_ckeditor extends ALT\Page
{
    public function get()
    {
        $this->addLib("ckeditor/ckeditor");
        $e = $this->createE(["content" => "hello world!"]);
        $e->add("ckeditor")->ckeditor("ckeditor");


        $this->write($this->createForm($e));
    }
}
