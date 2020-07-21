<?php
//Created by Raymond Chong
//Date: 2020-06-26
class DynamicPage_index extends ALT\Page
{
    public function get()
    {
        $tab = $this->createTab();
        $tab->add("All", "list");

        $this->write($tab);
    }
}
