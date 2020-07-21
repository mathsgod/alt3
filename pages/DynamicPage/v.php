<?php

//Created by Raymond Chong
//Date: 2020-06-26
class DynamicPage_v extends ALT\Page
{
    public function get()
    {
        $this->navbar->addButton("Input data", $this->object()->uri("edit_data"));
        $v = $this->createV();
        $v->add("Name", "name");
        $v->add("Path", "path");

        $v->add("Preview", function ($o) {

            $file = pathinfo($o->path, PATHINFO_FILENAME);
            return "<iframe src='/$file' style='width:100%;height:600px'></iframe>";
        });

        $this->write($v);
    }
}
