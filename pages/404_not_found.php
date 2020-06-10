<?php

class _404_not_found extends ALT\Page
{
    public function get()
    {
        $this->header->title = "Page not found";
    }
}
