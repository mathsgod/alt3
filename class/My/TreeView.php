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

	public function loadData(array $arr)
	{
		$this->renderTree($this, $arr);
	}

	private function renderTree($tree, array $arr)
	{
		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$folder = $tree->addFolder($k);
				$this->renderTree($folder, $v);
			} else {
				if (is_string($k)) {
					$folder = $tree->addFolder($k);
					$folder->addFile($v);
				} else {
					$tree->addFile($v);
				}
			}
		}
	}
}
