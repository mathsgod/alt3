<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated:
class ACL_index extends ALT\Page
{
    public function get()
    {
        $this->navbar()->addButton("Edit", "ACL/edit")->icon("fa fa-fw fa-edit");
        //$this->navbar()->addButton("Module acl", "ACL/ae_module");

        $tab = $this->createTab();
        $tab->add("All ACL", "list");
        $this->write($tab);
    }
}
