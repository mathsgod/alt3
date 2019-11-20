<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Updated Date: 10/5/2018
// Last Updated:
class SystemBackup_index extends ALT\Page
{
    public function get()
    {
        // check right
        $folder = getcwd() . "/backup";
        if (!is_writable($folder)) {
            $this->callout->warning("Warning", "Permission of $folder is not writable");
        } else {
            $this->navbar()->addButton("Backup now", "SystemBackup/do_run")->addClass("confirm btn-primary");
        }

        $mtb = $this->createTab();
        $mtb->add("All Backup", "list");
        $this->write($mtb);
    }
}
