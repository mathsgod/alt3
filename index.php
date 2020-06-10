<?php
date_default_timezone_set('Asia/Hong_Kong');
ini_set("display_errors", "On");
error_reporting(E_ALL && ~E_WARNING);
setlocale(LC_ALL, 'en_US.UTF-8'); //do not remove

$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");
header("Access-Control-Allow-Headers: *");


$loader = require_once(__DIR__ . "/vendor/autoload.php");

/*use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$log = new Logger('App');
$log->pushHandler(new StreamHandler(__DIR__ . '/logs/' . date("Y-m-d") . ".log", Logger::DEBUG));*/

session_start();
$app = new App\App(__DIR__);
$app->run();

