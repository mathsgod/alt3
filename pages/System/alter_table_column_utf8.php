<?php

class System_alter_table_column_utf8 extends App\Page
{
    public function get()
    {
        foreach ($this->app->db->query("show tables") as $table) {
            foreach ($table as $t) {
                foreach ($this->app->db->query("describe `$t`") as $row) {
                    if (strtolower(substr($row["Type"], 0, 7)) == "varchar" || strtolower($row["Type"]) == "text" || strtolower($row["Type"]) == "longtext") {
                        $field = $row["Field"];
                        $type = $row["Type"];
                        $sql = "ALTER TABLE  `$t` CHANGE COLUMN `{$field}` `{$field}` {$type} NULL DEFAULT NULL;";
                        $this->alert->info($sql);
                        try {
                            $this->app->db->exec($sql);
                        } catch (Exception $e) {
                            $this->alert->danger($e->getMessage());
                        }
                    }
                }
            }
        }
        $this->redirect();
    }
}
