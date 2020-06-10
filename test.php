<?php
date_default_timezone_set('Asia/Hong_Kong');
ini_set("display_errors", "On");
error_reporting(E_ALL && ~E_WARNING);
setlocale(LC_ALL, 'en_US.UTF-8'); //do not remove

$loader = require_once(__DIR__ . "/vendor/autoload.php");

$app = new App\App(__DIR__);



$u = new App\User(1);
print_r($u->UserGroup()->toArray());
