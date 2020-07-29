<?php

class DynamicPage_test2 extends App\Page
{
    public function get()
    {
        $dp = App\DynamicPage::_("b");


        $this->data = $dp->data;
        $this->data["bbb"]=1;
    }
}
