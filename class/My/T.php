<?php

namespace My;

class T extends \P\Query
{
	public $objects;
	public $tr;
	public $tbody;
	public $thead;
	public $columns;
	public function __construct($objects)
	{
		parent::__construct("table");
		$this->addClass('table table-hover');
		$this->objects = $objects;
		$this->tbody = p("tbody")->appendTo($this);
		$this->columns = p();
		$this->tr = p();

		foreach ($objects as $k => $obj) {
			$tr = p("tr")->appendTo($this->tbody);
			$tr->data("object", $obj);

			if ($obj instanceof \R\Model) {
				$tr->attr("index", $obj->id());
				$tr->attr("data-index", $obj->id());
			} else {
				$tr->attr("data-index", $k);
			}

			$this->tr[] = $tr[0];
		}
	}

	public function thead()
	{
		if ($this->thead)
			return $this->thead;
		$this->thead = p("thead")->prependTo($this);
		return $this->thead;
	}

	public function add($label = "", $getter = null)
	{

		$column = new C("th");
		$this->columns[] = $column;

		if ($this->hasClass("form-create")) {
			$column->createTemplate = true;
		}

		$thead = $this->thead();
		$th = p($column)->appendTo($thead);
		$th->text($label);
		$column->cell = new \P\Query;
		$i = 0;
		foreach ($this->objects as $k => $obj) {
			$tr = $this->tr[$i++];
			$td = p("td")->appendTo($tr);
			$td->data("object", $obj);
			$column->cell[] = $td[0];
			if ($getter) {
				if ($getter instanceof \Closure) {
					$td->html(call_user_func_array($getter, [$obj, $k]));
				} else {
					$td->text(Func::_($getter)->call($obj));
				}
			}
		}

		$form_name = $this->attr("form-name");

		$column->callback = function ($object, $node) use ($form_name) {
			$field = $node->attributes["data-field"];

			$tr = p($node)->closest("tr");

			$id = $tr->attr("data-index");

			$fn = "_u";
			if ($form_name)
				$fn = $form_name . "[u]";

			if ($node->attributes["multiple"]) {
				$node->attributes["name"] = "{$fn}[$id][$field][]";
			} else {
				$node->attributes["name"] = "{$fn}[$id][$field]";
			}
		};

		return $column;
	}

	public function row()
	{
		return $this->tr;
	}

	public function addDel()
	{
		$column = $this->add();
		$column->width(20);
		$as = $column->a()->addClass("btn btn-xs btn-danger confirm")->removeClass("btn-default")->html("<i class='fa fa-times'></i>");
		foreach ($as as $a) {
			if ($object = p($a)->parent()->data("object")) {
				p($a)->attr('href', $object->uri('del'));
			}
		}
		return $as;
	}
	public function addEdit()
	{
		$column = $this->add();
		$column->width(20);
		$as = $column->a()->addClass("btn btn-xs btn-warning")->removeClass("btn-default")->html("<i class='fa fa-pencil-alt'></i>");

		foreach ($as as $a) {
			if ($object = p($a)->parent()->data("object")) {
				p($a)->attr('href', $object->uri('ae'));
			}
		}
		return $as;
	}

	public function __toString()
	{
		foreach ($this->columns as $column) {
			if ($column->c_tpl->count()) {
				$column->attributes["c-tpl"] = (string )$column->c_tpl;
			}
		}
		return parent::__toString();
	}

	public function addChildRow($label, $callback)
	{
		return $this->add(
			$label,
			function ($obj) use ($callback) {
				$childHTML = $callback($obj);
				if ($childHTML === null) {
					return;
				}

				$btn = p("button")->html("<i class='fa fa-chevron-up'></i>")->addClass("btn btn-xs btn-primary table-childrow-btn table-childrow-close");
				$btn->attr('data-child', $childHTML);

				return $btn;
			}
		);
	}
}
