<?php
class System_example_tabs extends ALT\Page
{
    public function get()
    {
        
        $tab = $this->createTab("A");
        $tab->setAttribute("id", "tab1");
        $tab->add("T1", "tabs/t1");
        $this->write($tab);
    }

    public function t1()
    {
        //App\UI\Tab::$_TabID = 100;
        $tab = $this->createTab("B");
        $tab->setAttribute("id", "tab2");
        $tab->add("T2", "tabs/t2");
        $this->write($tab);
    }

    public function t2()
    {
        $this->write("T2content");
    }
}
