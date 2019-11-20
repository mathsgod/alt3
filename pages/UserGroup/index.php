<?php

class UserGroup_index extends ALT\Page
{
	public function get()
	{
		$this->header->title = "User group";
		$tab = $this->createTab();
		$tab->add("All user group", "list");
		$this->write($tab);
	}
}
