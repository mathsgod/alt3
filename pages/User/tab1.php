<?php
class User_tab1 extends ALT\Page
{
    public function get()
    {
        $tab = $this->createTab("A");
        $tab->add("Tab A", "tab_a", "1");
        $this->write($tab);
    }
}