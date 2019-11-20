<?
class System_db_procedure extends ALT\Page
{
    public function get()
    {

        $t=$this->createT($this->app->db->procedure);
        $t->add("Db","Db");
        $t->add("Name","Name");
        $t->add("Comment","Comment")->format('nl2br');

        $this->write($t);

    }
}
