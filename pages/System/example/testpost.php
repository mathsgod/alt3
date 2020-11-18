<?php
class System_example_testpost extends ALT\Page
{
    public function post()
    {
        outP($_POST);
        die();
        //$this->alert->info("hello");
        //$this->redirect("Dashboard");
    }

    public function get()
    {
        $this->write($this->createForm("hello"));
    }
}
