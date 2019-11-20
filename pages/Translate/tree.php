<?php

class Translate_tree extends App\Page
{
	public function get()
	{

		$t = new My\TreeView();

		foreach ($this->app->getModule() as $module) {
			$folder = $t->addFolder($module);
			foreach ($this->app->config["language"] as $lang => $language) {
				$f1 = $folder->addFolder($language);

				foreach (App\Translate::ByModule($module->name, $lang) as $k => $v) {
					$f1->addFile($k . " → " . $v);
				}
			}

		}

		$this->write($t);
	}
}
