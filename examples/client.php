<?php

require_once('../include.php');

$request = JSONRPC::request('math.add', array(1, 4));

$host = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);
$result = JSONRPC::send($request, "http://{$host}{$path}/server.php");
var_dump($result);

?>