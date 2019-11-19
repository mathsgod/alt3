<?php
namespace My;
class Tab extends Query {
	public $navs;
	public $content;
	public static $_TabID = 0;
	public static $_MyTab = 0;
	private $route;

	public function __construct($route) {
		parent::__construct("div");
		$this->route = $route;
		$this->addClass("nav-tabs-custom");

		$this->navs = p("ul")->addClass("nav nav-tabs");
		$this->append($this->navs);

		$this->content = p("div")->addClass("tab-content");
		$this->append($this->content);

		self::$_MyTab++;
		$module = $route->module;

		$this->attr("data-cookie", $module->name . "/" . $route->action . "/" . self::$_MyTab);
		$this->addClass("my_tab");
	}

	public function add($uri, $label, $t = null) {
		self::$_TabID++;
		$tab_id = self::$_TabID;

		if ($this->route->id) {
			$href = $this->route->module->name . "/" . $this->route->id . "/" . $uri;
		} else {
			$href = $this->route->module->name . "/" . $uri;
		}

		if (isset($t)) {
			$href .= "?t=$t";
		}
		// if (!ACL::Allow($href))return;
		$li = p("li");
		$a = p("a")->attr("href", $href)->text($label)->appendTo($li);
		$a->attr("data-target", "#tab-$tab_id");
		$a->attr("data-toggle", "tabajax");
		$this->navs->append($li);

		$div = p("div")->appendTo($this->content);
		$div->addClass("tab-pane")->attr("id", "tab-$tab_id");
		return $li;
	}
}

?>