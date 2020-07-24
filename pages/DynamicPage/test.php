<?php

class DynamicPage_test extends ALT\Page
{
    public function get()
    {
        $dp = DynamicPage::_("a");


        $this->data["b"] = "bbb";
        $this->data["a"] = "hello";

        $this->data["list1"] = $dp->data["list1"];
        //Twig\Dynamic\Extension::SetData($dp->data);
    }
}
