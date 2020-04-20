<?php
class System_example_createE2 extends ALT\Page
{
    public function get()
    {
        $m = $this->createE([]);
        $m->add("Name")->input("name_tc")->required();
        $m->add("Name en")->input("name_en")->required();
        $m->add("Name sc")->input("name_sc")->required();
        $m->addSplit();

        $m->add("Name")->input("name_tc")->required();
        $m->add("Name en")->input("name_en")->required();
        $m->add("Name sc")->input("name_sc")->required();

        $m->addBreak();
        $m->addHr();
        $m->add("Content tc")->ckeditor("content_tc");
        $m->add("Content sc")->ckeditor("content_sc");
        $m->add("Content en")->ckeditor("content_en");


        $this->write($this->createForm($m));
    }
}
