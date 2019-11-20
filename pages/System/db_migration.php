<?php
class System_db_migration extends ALT\Page
{
    public function post()
    {
        $db = new DB\PDO($_POST["database"], $_POST["hostname"], $_POST["username"], $_POST["password"]);
        $this->migrate($this->app->db, $db);
    }

    public function get()
    {
        $this->write("<h4>Input production server</h4>");
        $this->write("<p>Generate sql script for upgrade production database</p>");

        $mv = $this->createE(new stdClass());
        $mv->add("Production server")->input("hostname")->required();
        $mv->add("Production username")->input("username")->required();
        $mv->add("Production password")->input("password")->required();
        $mv->add("Production db name")->input("database")->required();

        $this->write($this->createForm($mv));
    }

    private function migrate($source, $target)
    {
        $target_tables = [];
        foreach ($target->query("show tables") as $table) {
            $target_tables[] = array_values($table)[0];
        }

        foreach ($source->query("show tables") as $table) {
            $table_name = array_values($table)[0];
            $table_info = $source->query("desc `$table_name`")->fetchAll();

            if (in_array($table_name, $target_tables)) {
                // table exist in target
                $this->updateTable($target, $table_name, $table_info);
            } else {
                // tabe not find in target, create it
                // outp($table_name . " create");
                $this->_createTable($target, $table_name, $table_info);
            }
        }
    }

    private function findColumn($info, $name)
    {
        foreach ($info as $i) {
            if ($i["Field"] == $name) {
                return $i;
            }
        }
    }

    private function hasDifferent($source_col, $target_col)
    {
        foreach ($source_col as $k => $v) {
            if ($k == "Key") continue;
            if ($source_col[$k] != $target_col[$k]) {
                return true;
            }
        }
        return false;
    }

    public function differentKey($source_col, $target_col)
    {
        if ($source_col["Key"] != $target_col["Key"]) {
            $field = $source_col["Field"];
            if ($source_col["Key"] == "MUL") {
                return "ADD INDEX `$field` (`$field` ASC)";
            }
        }
        return false;
    }

    private function updateTable($db, $table, $source_info)
    {
        // fetch target table
        $sql = "ALTER TABLE `$table`\n";
        $diff = [];
        $target_info = $db->query("desc `$table`")->fetchAll();
        foreach ($source_info as $col) {
            $target_column = $this->findColumn($target_info, $col["Field"]);

            if ($target_column) {
                // check diff
                if ($this->hasDifferent($col, $target_column)) {
                    $diff[] = "CHANGE COLUMN `$col[Field]` " . $this->columnQuery($col);
                }
                if ($d = $this->differentKey($col, $target_column)) {
                    $diff[] = $d;
                }
            } else {
                $diff[] = "ADD COLUMN " . $this->columnQuery($col);
            }
        }

        if (sizeof($diff)) {
            $sql .= implode(",\n", $diff);
            $sql .= ";";
            outp($sql);
        }
    }


    private function _createTable($db, $table, $info)
    {
        // check target exist
        $sql = "CREATE TABLE `$table` (\n";
        foreach ($info as $col) {
            $s[] = $this->columnQuery($col);
            if ($col["Key"] == "PRI") {
                $s[] = "PRIMARY KEY (`{$col[Field]}`)";
            } elseif ($col["Key"] == "MUL") {
                $s[] = "INDEX `{$col[Field]}` (`{$col[Field]}` ASC)";
            }
        }

        $sql .= implode(",\n", $s);
        $sql .= ");";

        outp($sql);
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
}