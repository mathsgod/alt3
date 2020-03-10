<?php

class System_db_check extends ALT\Page
{

	public function get()
	{

		$t = [];
		$sql = $this->findSQL();

		if (sizeof($sql) == 0) {
			$this->callout->info("Information", "Your database is lastest version!");
			return;
		}

		foreach ($sql as $s) {
			$t[] = nl2br($s, "<br/>");
		}

		$form = $this->createForm(implode("<br/>", $t) . "<br/> Updated?");
		$form->card->header->title = "Following SQL should be update";

		$this->write($form);
	}

	private function findColumn($columns, $name)
	{
		foreach ($columns as $i) {
			if ($i->Field == $name) {
				return $i;
			}
		}
	}

	private function hasDifferent($source_col, $target_col)
	{
		foreach ($source_col as $k => $v) {

			if ($k == "Key")
				continue;
			if ($source_col->$k != $target_col[$k]) {
				return true;
			}
		}
		return false;
	}

	private function checkDifferent($source, $target)
	{
		$diff = [];
		foreach ($target as $column) {
			$col = $this->findColumn($source, $column["Field"]);
			if ($col) {
				if ($this->hasDifferent($col, $column)) {
					$diff[] = "CHANGE COLUMN `{$col->Field}` " . $this->columnQuery($column);
				}
			} else {
				$diff[] = "ADD COLUMN " . $this->columnQuery($column);
			}
		}
		return $diff;
	}

	private function updateTable($table, $changes)
	{
		// fetch target table
		$sql = "ALTER TABLE `$table`\n";
		$diff = [];
		$diff = $this->checkDifferent($table->columns(), $changes);
		if (sizeof($diff)) {
			$sql .= implode(",\n", $diff);
			$sql .= ";";
			return $sql;
		}

		return "";
	}

	public function createATable($table, $changes)
	{
		// check target exist
		$sql = "CREATE TABLE `$table` (\n";
		foreach ($changes as $col) {
			$s[] = $this->columnQuery($col);
			if ($col["Key"] == "PRI") {
				$s[] = "PRIMARY KEY (`{$col[Field]}`)";
			} elseif ($col["Key"] == "MUL") {
				$s[] = "INDEX `{$col[Field]}` (`{$col[Field]}` ASC)";
			}
		}

		$sql .= implode(",\n", $s);
		$sql .= ");";
		return $sql;
	}

	private function columnQuery($col)
	{
		$q = "`$col[Field]` {$col[Type]}";
		if ($col["Null"] == "NO") {
			$q .= " NOT NULL";
		}
		$q .= " " . $col['Extra'];
		if ($col["Default"] != "") {
			$q .= " Default '" . $col['Default'] . "'";
		}

		return $q;
	}

	public function post()
	{
		$db = $this->app->db;
		foreach ($this->findSQL() as $sql) {
			$db->exec($sql);
		}
		$this->alert->info("SQL executed");
		$this->redirect();
	}

	public function findSQL()
	{
		$pi = $this->app->pathinfo();
		$db_scheme = json_decode(file_get_contents($pi["system_root"] . "/db_schema.json"), true);


		$db = $this->app->db;

		$schema = $db;

		$tables = [];

		foreach ($schema->tables as $table) {
			$tables[$table->name] = $table;
		}

		$sql = [];
		foreach ($db_scheme as $table => $columns) {
			if (!$tables[$table]) {
				$sql[] = $this->createATable($table, $columns);
			} else {
				if ($q = $this->updateTable($tables[$table], $columns)) {
					$sql[] = $q;
				}
			}
		}

		return $sql;
	}

	public function needUpdate()
	{
		$sql = $this->findSQL();
		return sizeof($sql);
	}
}
