<?php
$dsn = 'mysql:dbname=raymond;host=127.0.0.1';
$user = 'root';
$password = '111111';

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    $db->exec("set names utf8");
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$tables = $db->query("show tables");

$data = [];
foreach ($tables as $t) {
    $table = array_values($t)[0];

    $data[$table] = $db->query("desc `$table`")->fetchAll();
}

echo json_encode($data, JSON_PRETTY_PRINT);
