<?php

class User_update extends ALT\Page {
	public function get() {
		$mv = $this->createE();
		$mv->add("Username")->input("username");
		$mv->add("First name")->input("first_name");
		$mv->add("Last name")->input("last_name");
		$mv->add("Email")->input("email");

		$this->write($this->createForm($mv));
	}
}
