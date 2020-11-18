<?php

/**
 * Created by: Raymond Chong
 * Date: 2020-11-18 
 */
class System_example_index extends ALT\Page
{
    public function get()
    {
        $tab = $this->createTab();
        $tab->add("All", "list");
        $this->write($tab);
    }
}
