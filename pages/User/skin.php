<?php

class User_skin extends ALT\Page
{
	public function get($name)
	{
		$u = $this->app->user;
		$u->skin = $name;
		$u->save();
		$this->redirect();
	}
}