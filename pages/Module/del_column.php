<?php
class Module_del_column extends App\Page
{

    public function get($table, $field)
    {
        $this->app->db->table($table)->dropColumn($field);
        $this->app->alert->success("Column deleted");
        $this->redirect();
    }
}
