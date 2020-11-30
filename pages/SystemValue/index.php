<?php

use App\SystemValue;

class SystemValue_index extends ALT\Page
{
	public function get()
	{
		$mtb = $this->createTab();
		$mtb->add("All SystemValue", "list");
		$this->write($mtb);
	}
}