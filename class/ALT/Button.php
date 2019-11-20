<?php

namespace ALT;

class Button extends \P\HTMLButtonElement
{
	public function __construct($type, $size)
	{

		parent::__construct();
		$this->classList->add("btn");
		if ($type) {
			$this->classList->add("btn-$type");
		}

		if ($size) {
			$this->classList->add("btn-$size");
		}
	}
}
