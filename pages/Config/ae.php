<?php

class Config_ae extends ALT\Page
{
	public function get($type)
	{
		$obj = $this->object();
		if ($obj->config_id) {
			$type = $obj->type;
		}

		$mv = $this->createE();
		$mv->add("Name")->input("name")->required();


		if ($type == "json") {
			$this->addLib("ace");
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
