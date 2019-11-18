<?php
class Module_add_column extends ALT\Page
{

    public function post($table)
    {
        $t = $this->app->db->table($table);

        $type = $_POST["Type"];
        if ($_POST["Length"]) {
            $type .= "(" . $_POST["Length"] . ")";
        }

        $constraint = "";

        if ($_POST["not_null"]) {
            $constraint = "NOT NULL";
        } else {
            $constraint = "NULL";
        }
        $t->addColumn($_POST["Field"], $type);

        $this->app->alert->success("Column added");
        $this->redirect();
    }

    public function get($table)
    {

        //$col=App::DB()->table($table);
        $e = $this->createE($col);

        $e->add("Field")->input("Field");
        $e->add("Type")->select("Type")->options(["int", "varchar", "tinyint", "text", "date", "time", "datetime", "longblob", "json"]);
        $e->add("Length")->input("Length");
        $e->add("Not null")->checkbox("not_null");


        $this->write($this->createForm($e));
    }
}
