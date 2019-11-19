<?php

namespace My;

class TreeView extends TreeNode
{
	public $options = [];

	public function __construct()
	{
		parent::__construct("div");
		$this->classList->add("jstree");
	}

	public function plugins($plugins = [])
	{
		$this->options["plugins"] = $plugins;
		$this->setAttribute("data-options", js_encode($this->options));
		return $this;
	}
}
