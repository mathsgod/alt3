<?php
class System_example_nav extends ALT\Page
{
    public function get()
    {
        $bg = $this->navbar->addButtonGroup();
        $bg->addA("test")->attr("href", "User/1/v");
        $bg->addA("test1")->attr("href", "User/1/v");
        $bg->addA("test2")->attr("href", "User/1/v");
        $bg->addButton("btn1");


        $dd = $this->navbar->addDropdown("drop down");

        $dd->addItem("User", "User/1/v");
        $dd->addItem("User2", "User/2/v");
        $dd->addItem("User3", "User/3/v");
    }
}
