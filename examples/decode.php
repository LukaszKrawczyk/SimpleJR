<?php

require_once('../include.php');

########## decode JSON-RPC request
$jsonrpc = '{"jsonrpc":"2.0","method":"add","params":[1,4],"id":1}';
$request = JSONRPC::decode($jsonrpc);
var_dump($request);

########## decode JSON-RPC response

$jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
$response = JSONRPC::decode($jsonrpc);
var_dump($response);
var_dump($response->isOK());

########## decode JSON-RPC error response

$jsonrpc = '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":1}';
$response = JSONRPC::decode($jsonrpc);
var_dump($response);
var_dump($response->isOK());

?>