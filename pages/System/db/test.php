<?

class System_db_test extends ALT\Page
{
    public function get()
    {
        $t = $this->createDataTable($this->app->db->tables);


        //$t->boxStyle();
        $t->add("Name", function ($o) {
            return html("a")->href("System/db/v_table?table=" . $o->name)->text($o->name);
        });

        //$this->write($this->createBox((string)$t));
        $this->write($t);
    }
}
