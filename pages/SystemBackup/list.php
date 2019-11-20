<?php

// Created By: Raymond Chong
// Last Updated:
use App\SystemBackup;

class SystemBackup_list extends App\Page
{
    public function get()
    {
        $files = array();
        foreach (glob(getcwd() . '/backup/*') as $file) {

            $fi = new SplFileInfo($file);
            $f = new SystemBackup(null);
            $f->filename = $fi->getFilename();
            $f->size = $fi->getSize() . " bytes";
            $files[] = $f;
        }


        $myt = $this->createDataTable($files);
        $myt->addDel();
        $myt->add("File name", "filename");
        $myt->add("Size", "size");
        $myt->add("Restore", function ($o) {
            $a = html('a');
            $a->text("restore");
            $a->class("btn btn-xs btn-danger confirm");
            $a->href($o->uri("restore"));
            return $a;
        });

        $this->write($myt);
    }
}
