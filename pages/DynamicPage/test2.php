<?php

class DynamicPage_test2 extends ALT\Page
{
    public function get()
    {
        $dp = App\DynamicPage::_("b");


        $this->data = $dp->data;
        //Twig\Dynamic\Extension::SetData($dp->data);
    }
}
