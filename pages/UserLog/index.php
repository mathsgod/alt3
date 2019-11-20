<?php

class UserLog_index extends ALT\Page
{
	public function get()
	{

		$nav = $this->navbar();
		$nav->addButton("Trucate", "UserLog/truncate")->addClass("confirm");
		//		$this->header("User Log");
		$tab = $this->createTab();
		$tab->add("All user log", "list");
		//$tab->add("All user log","list1");
		$this->write($tab);
	}
}
