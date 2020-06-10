<?phpclass System_db_trigger extends ALT\Page
{
    public function get()
    {
        $sql= "SHOW TRIGGERS";
        $rs = $this->app->db->query($sql);

        $t=$this->createT($rs->fetchAll());

        $t->add("Trigger","Trigger");
        $t->add("Event","Event");
        $t->add("Table","Table");
        $t->add("Statement","Statement")->format('nl2br');
        $t->add("Timing","Timing");
        $t->add("Created","Created");
        $t->add("Database collation","Database Collation");

        $this->write($t);

    }
}