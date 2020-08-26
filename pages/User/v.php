<?php

class User_v extends ALT\GridPage
{
	public function get()
	{
		$this->navbar->showEdit();
		$obj = $this->object();

		$this->navbar->addButton("Reset password", $obj->uri("reset_password"));
		$this->navbar->addButton("User group", $obj->uri("e_userlist"));

		if ($this->app->user->isAdmin()) {
			if ($obj->secret) {
				$this->navbar->addButton("Remove 2-step", $obj->uri("remove_2step"));
			}
		}

		//$this->navbar()->addLayoutReset();

		$mv = $this->createV();
		$mv->header->title = "Information";
		$mv->add("Username", "username");
		$mv->add("First name", "first_name");
		$mv->add("Last name", "last_name");
		$mv->add("Phone", "phone");
		$mv->add("Email", "email");
		$mv->add("Address", function ($o) {
			return $o->addr1 . "<br/>" . $o->addr2 . "<br/>" . $o->addr3;
		});
		$mv->add("Join date", "join_date");
		$mv->add("Expiry date", "expiry_date");
		$mv->add("Default page", "default_page");
		$mv->add("Language", "language");
		$mv->add("Status", "Status()");
		//		$mv->add("Status", "status")->format("tick");
		// $grid = $this->createGrid([1]);
		// $grid->add($mv, [0, 0]);
		// $mv->footer()->button()->text("test");
		$this->write($mv);

		$tab = $this->createTab();
		$tab->add("UserGroup", "v_usergroup");
		if ($this->app->user->isAdmin()) {
			$tab->add("UserLog", "v_userlog");
			// $tab->add("v_module", "Module ACL");
			// $tab->add("v_apikey", "API Key");
		}
		$this->write($tab);
	}
}
