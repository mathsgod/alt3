<?php

class DynamicPage_test extends ALT\Page
{
    public function get()
    {
        $dp = DynamicPage::_("a");

        outp($dp->data);
        Twig\Dynamic\Extension::SetData($dp->data);
    }
}
