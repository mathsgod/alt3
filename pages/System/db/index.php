<?phpclass System_db_index extends ALT\Page
{
	public function get()
	{

		$t = $this->createTab();
//        $t->add("Test","test");
		$t->add("Table", "table");
		$t->add("Trigger", "trigger");
		$t->add("Function", "function");
		$t->add("Procedure", "procedure");
		$this->write($t);
	}
}

