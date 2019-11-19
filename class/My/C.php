<?php

namespace My;

use P\Element;

class C extends Element
{
	private $_format;
	public $cell;
	public $label;
	public $c_tpl;

	public function cell()
	{
		return $this->cell;
	}

	public function __construct(string $tag)
	{
		parent::__construct($tag);
		$this->cell = p();
		$this->c_tpl = p();
	}

	public function gf($gf)
	{
		foreach ($this->cell as $cell) {
			p($cell)->attr("data-gf", $gf);

			if ($object = p($cell)->data("object")) {
				$field = p($cell)->attr("data-field");
				$gf_obj = Func::_($field)->call($object);
				p($cell)->text(Func::_($gf)->call($gf_obj));
			}
		}
		return $this;
	}

	public function iconpicker($field)
	{
		$p = new \BS\ButtonCollection;

		foreach ($this->cell as $cell) {
			$btn = new \BS\Button();
			$p->append($btn);
			$btn->attributes["data-iconset"] = "fontawesome";
			$btn->attributes["role"] = "iconpicker";
			$btn->attributes["data-rows"] = 10;
			$btn->attributes["data-cols"] = 10;
			$btn->attributes["name"] = $field;

			p($cell)->append($btn);

			if ($object = p($cell)->data("object")) {

				$btn->attributes["data-icon"] = Func::_($field)->call($object);
			}
		}
		return $p;
	}

	public function alink($uri)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				$field = p($cell)->attr("data-field");
				$next_obj = Func::_($field)->call($object);
				if (is_object($next_obj)) {
					$object = $next_obj;
				}

				$href = $object->uri($uri);

				$a = p("a")->attr('href', $href);
				$a->append(p($cell)->contents());
				$a->appendTo(p($cell));
			}
		}
		return $this;
	}

	public function width($width)
	{
		p($this)->css("width", "{$width}px");
		return $this;
	}

	public function attr($name, $value)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				if ($value instanceof \Closure) {
					$value = $value->call($object);
				}
			}
			p($cell)->attr($name, $value);
		}
		return $this;
	}

	public function format($callback, $params = null)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				$content = p($cell)->html();
				if (is_array($callback)) {
					$content = call_user_func($callback, $content, $params);
				} else {
					$content = \My\Func::_($callback)->call($content);
				}

				p($cell)->html($content);
			}
		}
		return $this;
	}

	public function input($field)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {
			$input = p("input")->appendTo($cell);

			$input->addClass('form-control');

			$input->attr("name", $field);

			$input->attr("data-field", $field);

			if ($object = p($cell)->data("object")) {
				$input->data("object", $object);
				$input->val(is_object($object) ?$object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
				}
			}


			$p[] = $input[0];
		}

		if ($this->createTemplate) {
			$input = p("input");
			$input->addClass('form-control');
			$input->attr("name", $field);
			$input->attr("data-field", $field);

			$p[] = $input[0];


			$this->c_tpl[] = $input[0];
		}
		return $p;
	}

	public function ckeditor($field)
	{
		$p = p();

		foreach ($this->cell as $cell) {
			$textarea = p("ckeditor")->appendTo($cell);
			$textarea->attr('data-field', $field);
			$textarea->attr('name', $field);
			$textarea->addClass('form-control');

			if ($object = p($cell)->data("object")) {
				$textarea->data("object", $object);
				$textarea->text(is_object($object) ?$object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $textarea[0]);
				}
			}

			$p[] = $textarea[0];
		}


		if ($this->createTemplate) {
			$textarea = p("ckeditor");
			$textarea->addClass('form-control');
			$textarea->attr("name", $field);
			$textarea->attr("data-field", $field);

			$p[] = $textarea[0];
			$this->c_tpl[] = $textarea[0];
		}

		return $p;
	}

	public function textarea($field)
	{
		$p = p();

		foreach ($this->cell as $cell) {
			$textarea = p("textarea")->appendTo($cell);
			$textarea->attr('data-field', $field);
			$textarea->attr('name', $field);
			$textarea->addClass('form-control');

			if ($object = p($cell)->data("object")) {
				$textarea->data("object", $object);
				$textarea->text(is_object($object) ?$object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $textarea[0]);
				}
			}

			$p[] = $textarea[0];
		}


		if ($this->createTemplate) {
			$textarea = p("textarea");
			$textarea->addClass('form-control');
			$textarea->attr("name", $field);
			$textarea->attr("data-field", $field);

			$p[] = $textarea[0];
			$this->c_tpl[] = $textarea[0];
		}

		return $p;
	}

	public function select($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ?$object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function Xeditable($index, $type = "text")
	{
		$p = p();
		foreach ($this->cell as $cell) {
			if ($type == "text") {
				$a = new \Xeditable\Text();
				$a->setAttribute("index", $index);
			} elseif ($type == "textarea") {
				$a = new \Xeditable\Textarea();
				$a->setAttribute("index", $index);
			} elseif ($type == "date") {
				$a = new \Xeditable\Date();
				$a->setAttribute("index", $index);
			} elseif ($type == "select") {
				$a = new \Xeditable\Select();
			} elseif ($type == "datetime") {
				$a = new \Xeditable\DateTime();
				$a->setAttribute("index", $index);
			} else {
				throw new \Exception("Xeditable type $type not found");
			}
			$a->appendTo($cell);

			if ($object = p($cell)->data("object")) {
				if ($type != "select") {
					$a->text(is_object($object) ?$object->$index : $object[$index]);
				}

				$a->setAttribute("data-pk", $object->id());
				$a->setAttribute("data-url", $object->uri() . "?xeditable");
			}

			$a->setAttribute("name", $index);
			$a->setAttribute("data-name", $index);

			$p[] = $a;
		}

		return $p;
	}

	public function ws($value = "pre")
	{
		$this->css("white-space", $value);
		return $this;
	}

	public function a($field = null)
	{
		$p = new \P\AnchorCollection;
		foreach ($this->cell as $cell) {
			$a = p("a")->appendTo($cell);
			$a->attr("data-field", $field);

			if ($object = p($cell)->data("object")) {
				$a->data("object", $object);
				$a->text(Func::_($field)->call($object));
			}
			$p[] = $a[0];

			if ($this->callback) {
				call_user_func($this->callback, $object, $a[0]);
			}
		}
		return $p;
	}

	public function email($field)
	{
		$input = $this->input($field);
		$input->attr("type", "email");

		foreach ($input as $i) {
			if (!$i->parentNode)
				continue;
			$div = p("div")->addClass("input-group");

			p($i)->wrap($div);

			$addon = p("span")->addClass("input-group-addon")->prependTo($div);
			$addon->append("<i class='fa fa-envelope-o'></i>");
		}
		return $input;
	}

	public function button()
	{
		$p = new \BS\ButtonCollection;
		foreach ($this->cell as $cell) {
			$btn = new HTML\Button();
			$btn->classList->add("btn-xs");
			p($cell)->append($btn);
			if ($object = p($cell)->data("object")) {
				p($btn)->data("object", $object);
			}

			$p[] = $btn;
		}
		return $p;
	}

	public function tokenField($field)
	{
		$p = $this->input($field);
		$p->attr("type", "hidden");

		$p = new \P\SelectCollection();
		foreach ($this->cell as $cell) {

			$is = new \BS\TokenField();
			p($is)->appendTo($cell);
			$is->attr("data-field", $field)->attr("name", $field . "[]");

			if ($object = p($cell)->data("object")) {
				$value = is_object($object) ?$object->$field : $object[$field];

				if ($value != "") {
					$is->attr("data-value", explode(",", $value));
				} else {
					$is->attr("data-value", "");
				}

				if ($this->callback) {
					call_user_func($this->callback, $object, $is);
				}
			}

			$p[] = $is;
		}

		if ($this->createTemplate) {
			$is = new \BS\TokenField();
			$is->attr("data-field", $field)->attr("name", $field . "[]");
			$p[] = $is;
			$this->c_tpl[] = $is;
		}

		return $p;
	}

	public function inputSelect($field)
	{
		$p = new \BS\InputSelectCollection();
		foreach ($this->cell as $cell) {
			$is = new \BS\InputSelect();
			p($is)->appendTo($cell);
			p($is)->find("input")->attr("data-field", $field)->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				p($is)->find("input")->val(is_object($object) ?$object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, p($is)->find("input")[0]);
				}
			}

			$p[] = $is;
		}

		if ($this->createTemplate) {
			$is = new \BS\InputSelect();
			p($is)->find("input")->attr("data-field", $field)->attr("name", $field);
			$p[] = $is;
			$this->c_tpl[] = $is;
		}
		return $p;
	}

	public function checkboxes($field)
	{
		$p = p();
		foreach ($this->cell as $cell) { }
		return $p;
	}

	public function checkbox($field)
	{
		//		$p = $this->input($field);
		//	$p->attr("type", "hidden");

		$p = p();
		foreach ($this->cell as $cell) {

			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("data-field", $field);
			$input->attr("name", $field);
			$input->val(0);
			call_user_func($this->callback, null, $input[0]);


			$cb = new \BS\CheckBox();
			p($cell)->append($cb);

			$input = $cb->find("input");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$input->addClass("iCheck");
			$input->val(1);

			if ($object = p($cell)->data("object")) {
				$value = is_object($object) ?$object->$field : $object[$field];
				if ($value) {
					$input->attr("checked", true);
				}

				if ($this->callback) {
					call_user_func($this->callback, $object, $cb->find("input")[0]);
				}
			}

			$p[] = $cb[0];
		}

		if ($this->createTemplate) {
			$cb = new \BS\CheckBox();
			$input = $cb->find("input");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$input->addClass("iCheck");
			$input->val(1);
			$this->c_tpl[] = $cb;
		}

		return $p;
	}

	public function colorpicker($index)
	{
		return $this->input($index)->addClass("cp");
	}

	public function date($field)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {

			$div = p("div")->addClass("input-group")->appendTo($cell);
			$addon = p("div")->addClass("input-group-addon")->appendTo($div);
			$addon->append('<i class="fa fa-calendar"></i>');


			$input = p("input")->appendTo($div);
			$input->attr("autocomplete", "off");
			$input->addClass("datetimepicker form-control");
			$input->attr("format", "YYYY-MM-DD");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$input->data("object", $object);
				$input->val(is_object($object) ?$object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
				}
			}
			$p[] = $input[0];
		}

		if ($this->createTemplate) {
			$div = p("div")->addClass("input-group");
			$addon = p("div")->addClass("input-group-addon")->appendTo($div);
			$addon->append('<i class="fa fa-calendar"></i>');

			$input = p("input")->appendTo($div);
			$input->addClass('datetimepicker form-control');
			$input->attr("format", "YYYY-MM-DD");
			$input->attr("name", $field);
			$input->attr("data-field", $field);

			$p[] = $input[0];

			$this->c_tpl[] = $div[0];
		}
		return $p;
	}

	public function time($field)
	{
		$p = p();
		foreach ($this->cell as $cell) {
			$div = p("div")->addClass("input-group bootstrap-timepicker")->appendTo($cell);
			$addon = p("div")->addClass("input-group-addon")->appendTo($div);
			$addon->append('<i class="fa fa-clock-o"></i>');

			$input = p("input")->appendTo($div);
			$input->addClass('form-control timepicker');
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$input->val(is_object($object) ?$object->$field : $object[$field]);


				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
				}
			}


			$p[] = $input;
		}

		if ($this->createTemplate) {
			$div = p("div")->addClass("input-group bootstrap-timepicker");
			$addon = p("div")->addClass("input-group-addon")->appendTo($div);
			$addon->append('<i class="fa fa-clock-o"></i>');
			$input = p("input")->appendTo($div);
			$input->addClass('form-control timepicker');
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$this->c_tpl[] = $div;
		}

		return $p;
	}

	public function datetime($index = null)
	{
		$p = $this->input($index);
		$p->addClass("datetimepicker");
		return $p;
	}

	public function multiSelect($field)
	{
		$p = new \P\SelectCollection();
		foreach ($this->cell as $cell) {
			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("data-field", $field);
			$input->attr("name", $field);

			$select = p("select")->appendTo($cell);
			$select->addClass("form-control multiselect");
			$select->attr("multiple", true);
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ?$object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$select->attr("name", $field . "[]");

			$p[] = $select[0];
		}


		return $p;
	}

	public function multiSelectPicker($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("selectpicker");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("data-actions-box", "true");
			$select->attr("name", $field . "[]");
			$select->attr("multiple", true);
			//$select->attr("data-width","fit");

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ?$object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("data-actions-box", "true");
			$select->attr("name", $field . "[]");
			$select->attr("multiple", true);


			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function selectPicker($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("form-control selectpicker");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ?$object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control selectpicker");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function multiSelect2($field)
	{
		foreach ($this->cell as $cell) {
			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				if ($this->callback) {

					call_user_func($this->callback, $object, $input[0]);
				}
			}
		}

		$select = $this->select2($field);
		$select->attr("multiple", true);
		$select->attr("name", $field . "[]");
		return $select;
	}

	public function select2($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("select2 form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				try {
					$data_value = is_object($object) ?$object->$field : $object[$field];
					if (!is_array($data_value)) {
						$data_value = explode(",", $data_value);
					}

					$select->attr("data-value", $data_value);
				} catch (\Exception $e) { }

				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("select2 form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);
			$this->c_tpl[] = $select;

			$p[] = $select[0];
		}
		return $p;
	}

	public function helpBlock($text)
	{
		$p = p();
		foreach ($this->cell as $cell) {
			$block = p("p")->appendTo($cell);
			$block->addClass("help-block");
			$block->html($text);
			$p[] = $block[0];
		}

		if ($this->createTemplate) {
			$block = p("p");
			$block->addClass("help-block");
			$block->html($text);
			$this->c_tpl[] = $block[0];
		}

		return $p;
	}

	public function img($field)
	{
		$p = p();
		foreach ($this->cell as $cell) {
			$img = p("img")->appendTo($cell);
			$img->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$img->attr("src", is_object($object) ?$object->$field : $object[$field]);
			}
			$p[] = $img[0];
		}
		return $p;
	}

	public function __toString()
	{
		if ($this->c_tpl) {
			$this->attributes["c-tpl"] = (string)$this->c_tpl;
		}
		return  parent::__toString();
	}
}
