<?phpclass System_db_v_table extends ALT\Page
{
    public function get($table)
    {
        $table = $this->app->db->table($table);

        $v = $this->createV($table);
        $v->add("Name", "name");
        $this->write($v);


        $t = $this->createT($table->columns);
        $t->add("Field", "Field");
        $t->add("Type", "Type");
        $t->add("Null", "Null");
        $t->add("Key", "Key");
        $t->add("Default", "Default");
        $t->add("Extra", "Extra");
        $this->write($t);

        
        $t = $this->createT($table->index);
        $t->add("Non unique", "Non_unique");
        $t->add("Key name", "Key_name");
        $t->add("Seq in index", "Seq_in_index");
        $t->add("Column name", "Column_name");
        $t->add("Collation", "Collation");
        $t->add("Cardinality", "Cardinality");
        $t->add("Null", "Null");
        $t->add("Index_type", "Index_type");
        $t->add("Comment", "Comment");
        $t->add("Index_comment", "Index_comment");
        $this->write($t);

        //$this->write()
        //outp($table);

        //outP($table);
    }
}
