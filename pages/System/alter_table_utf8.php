<?php

class System_alter_table_utf8 extends ALT\Page
{
    public function post()
    {
        $charset = $_POST["charset"];

        foreach ($this->app->db->query("SHOW CHARACTER SET")->fetchAll() as $c) {
            if ($c["Charset"] == $charset) {
                $collation = $c["Default collation"];
                break;
            }
        }

        foreach ($this->app->db->query("show tables") as $table) {
            foreach ($table as $t) {
                try {
                    $this->alert->info("ALTER TABLE  `$t` DEFAULT CHARACTER SET {$charset} COLLATE {$collation};");
                    $this->app->db->exec("ALTER TABLE  `$t` DEFAULT CHARACTER SET {$charset} COLLATE {$collation};");
                } catch (Exception $e) {
                    $this->alert->error($e->getMessage());
                }
            }
        }
        $this->redirect();
    }

    public function get()
    {
        $e = $this->createE(["charset" => "utf8mb4"]);

        $charset = array_map(function ($o) {
            return $o["Charset"];
        }, $this->app->db->query("SHOW CHARACTER SET")->fetchAll());

        sort($charset);
        $e->add("Charset")->select("charset")->options($charset);
        $this->write($this->createForm($e));
    }
}
