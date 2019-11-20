<?php
// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated: 2013-04-11
class EventLog_index extends ALT\Page
{
	public function get()
	{
		$nav = $this->navbar();
		$nav->addButton("Truncate", "EventLog/truncate")->addClass("confirm");

		$mtb = $this->createTab();
		$mtb->add("All event log", "list");
		$this->write($mtb);
	}
}
