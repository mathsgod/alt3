<?php

class UI_clear extends App\Page
{
    public function get()
    {
        $this->alert->info("Truncate UI");
        $this->app->db->exec("truncate UI");
        $this->redirect("System");
    }
}