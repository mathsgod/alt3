<?php
class Module_alter_column extends ALT\Page
{

    public function post($table, $field)
    {
        $col = $this->app->db->table($table)->columns($field);
        $col->rename($_POST["Field"]);
        $this->app->alert->success("Column field changed");
        $this->redirect();
    }

    public function get($table, $field)
    {

        $col = $this->app->db->table($table)->column($field);
        $e = $this->createE($col);
        $e->add("Field")->input("Field")->required();
        $e->add("Type", "Type");
        $e->add("Null", "Null");
        $e->add("Key", "Key");
        $e->add("Default", "Default");
        $e->add("Extra", "Extra");
        $this->write($this->createForm($e));
    }
}
