<?php

class Translate_index extends ALT\Page
{
    public function get()
    {
        //        $this->addLib("jstree");
        //$this->navbar()->addButton("Translate all", "Translate/all");

        $tab = $this->createTab();
        $tab->add("All Translate", "list");
        //$tab->add("Translate tree", "tree");
        $this->write($tab);
    }
}
