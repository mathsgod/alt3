<?php

class Config_ae extends ALT\Page
{
	public function get($type)
	{
		$obj = $this->object();
		if ($obj->config_id) {
			$type = $obj->type;
		}

		$card = $this->createCard();
		$form = $card->addForm($obj);
		$form->add("Name")->input("name")->required();

		if ($type == "json") {
			$form->add("Value")->ace("value", "json");
		} else {
			$form->add("Value")->input("value");
		}

		$this->write($card);
		return;

		$mv = $this->createE();
		$mv->add("Name")->input("name")->required();


		if ($type == "json") {
			$mv->add("Value")->ace("value", "json");
		} else {
			$mv->add("Value")->input("value");
		}
		$f = $this->createForm($mv);
		if ($type) {
			$f->addHidden("type", $type);
		}

		$this->write($f);
	}
}
