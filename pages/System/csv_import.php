<?php

// Created By: Raymond Chong
// Created Date: 19/2/2010
// Last Updated:
class System_csv_import extends ALT\Page
{
	public $delimiter = array(",", "\\t");

	public function post()
	{
		$content = $_POST["content"];

		$columns = array();
		foreach (explode("\n", $_POST["column"]) as $col) {
			$col = trim($col);
			if ($col == "")
				continue;
			$columns[] = $col;
		}

		if ($_POST["delimiter"] == 1) {
			$delimiter = "\t";
		} else {
			$delimiter = $this->delimiter[$_POST["delimiter"]];
		}

		$temp = fopen("php://memory", "rw");
		fwrite($temp, $_POST["content"]);
		fseek($temp, 0);
		$r = array();
		while (($data = fgetcsv($temp, 4096, $delimiter)) !== false) {
			$r[] = $data;
		}
		fclose($temp);

		foreach ($r as $d) {
			$o = array();
			foreach ($columns as $k => $col) {
				if ($_POST["trim_value"]) {
					$o[$col] = trim($d[$k]);
				} else {
					$o[$col] = $d[$k];
				}
			}
			$this->app->db->insert($_POST["table"], $o);
		}
		$this->alert->info("Import done");
		$this->redirect("System/csv_import");
	}

	public function get()
	{
		$mv = $this->createE();
		$mv->add("Target table")->input("table")->required();;
		$mv->add("Delimiter")->select("delimiter")->ds($this->delimiter);
		$mv->add("Trim Value")->checkbox("trim_value");
		$mv->add("Column")->textarea("column")->attr("placeholder", "一行一個");
		$mv->add("Content")->textarea("content");

		$this->write($this->createForm($mv));
	}
}

