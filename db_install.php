<?

$db_scheme = json_decode(file_get_contents(__DIR__ . "/db_schema.json"), true);

function columnQuery($col)
{
    $q = "\t `$col[Field]` {$col[Type]}";
    if ($col["Null"] == "NO") {
        $q .= " NOT NULL";
    }
    $q .= " " . $col['Extra'];
    if ($col["Default"] != "") {
        $q .= " Default '" . $col['Default'] . "'";
    }

    return $q;
}


$sqls = [];
foreach ($db_scheme as $tab => $fields) {
    $sql = "CREATE TABLE `$tab` (\n";
    $s = [];
    foreach ($fields as $col) {
        $s[] = columnQuery($col);
        if ($col["Key"] == "PRI") {
            $s[] = "\t PRIMARY KEY (`{$col[Field]}`)";
        } elseif ($col["Key"] == "MUL") {
            $s[] = "\t INDEX `{$col[Field]}` (`{$col[Field]}` ASC)";
        }
    }

    $sql .= implode(",\n", $s);
    $sql .= ");\n";

    $sqls[] = $sql;
}

echo implode("\n", $sqls);
