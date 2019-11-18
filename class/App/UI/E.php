<?php

namespace App\UI;

use App\Page;

class E extends \ALT\E
{
	private $_page;
	public function __construct($object, Page $page)
	{
		$this->_page = $page;
		parent::__construct($object);
	}
	public function add($label, $getter = null)
	{
		return parent::add($this->_page->translate($label), $getter);
	}
}
