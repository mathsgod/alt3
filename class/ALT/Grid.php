<?php

namespace ALT;

use App\UI\Card;

class Grid extends \P\Query
{
	private static $NUM = 0;
	private $item = 0;
	public $layout;
	public $sortable = true;
	private $_location = [];
	public function __construct(array $sizes)
	{
		parent::__construct("div");
		$this->attr("is", "grid");
		$this->attr("grid-num", self::$NUM);
		self::$NUM++;

		foreach ($sizes as $s) {
			$row = $this->addRow();
			foreach (range(1, $s) as $a) {
				$col = floor(12 / $s);
				$section = p("section");
				$section->attr("is", "grid-section");
				$section->addClass("col-md-$col ui-sortable connectedSortable");
				$row->append($section);
			}
		}
	}

	private $_row = [];
	public function addRow()
	{
		$row = p("div");
		$row->addClass('row');
		$this->append($row);
		$this->_row[] = $row;
		return $row;
	}

	private function findRowSection($n): array
	{
		foreach ($this->layout as $row => $rows) {

			foreach ($rows as $section => $sections) {

				foreach ($sections as  $seq => $num) {

					if ($num == $n) {

						return [$row, $section, $seq];
					}
				}
			}
		}
		return [0, 0, 0];
	}

	public function add(Card $card,  $location = [0, 0, 0])
	{
		if ($card instanceof Card) {
			$card->collapsible(true);
			$card->pinable(true);
			p($card)->attr("grid-item", $this->item);
		}

		if ($this->layout) {
			$location = $this->findRowSection($this->item);
		}

		$row = $this->_row[$location[0]];
		p($card)->attr("grid-sequence", $location[2]);


		$section = $row->find("section")[$location[1]];
		p($section)->append($card);


		$this->item++;

		//order this seq
		$divs = [];

		foreach (p($section)->children("div") as $d) {
			$divs[] = $d;
		}

		usort($divs, function ($a, $b) {
			return $a->getAttribute("grid-sequence") <=> $b->getAttribute("grid-sequence");
		});

		foreach ($divs as $div) {
			p($section)->append($div);
		}

		return $this;
	}
}
