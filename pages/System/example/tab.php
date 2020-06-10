<?php
class System_example_tab extends ALT\Page
{
    public function get()
    {
        $this->write("tab");
        $tab = $this->createTab();
        $tab->addLocal("label1", "content1");
        $tab->addLocal("label2", "content2");
        $tab->addLocal("label3", "content3");
        $tab->add("Label", "User/1/v");
        $this->write($tab);
    }
}
