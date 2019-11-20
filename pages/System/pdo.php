<?php

class System_pdo extends ALT\Page
{
    public function clear()
    {
        $this->app->db->query("Delete from pdo_log");
        $this->alert->info("pdo_log cleared");
        $this->redirect("System/pdo");
    }

    public function get()
    {
        $h = $this->navBar();
        $h->addButton("Clear pdo", "System/pdo/clear");

        $mt = $this->createT($this->app->db->query("select * from pdo_log"));
        $mt->add("SQL", "sql");
        $mt->add("Date", function ($obj) {
            return $obj["date"];
        });

        $this->write($mt);
    }
}
