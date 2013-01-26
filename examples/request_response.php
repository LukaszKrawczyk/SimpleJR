<?php

require_once('../include.php');

########## Create JSON-RPC request

$request = JSONRPC::request('math.add', array(1, 4)); // create request object
var_dump($request);

var_dump($request->toJSONRPC()); // print JSON-RPC
// or
var_dump(JSONRPC::request('math.add', array(1, 4))->toJSONRPC()); // just print JSON-RPC

// Create JSON-RPC request with named parameters

$request = JSONRPC::request();
$request->method = 'users.getUser';
$request->params = array('firstName' => 'Jan', 'lastName' => 'Nowak');
var_dump($request);

var_dump($request->toJSONRPC()); // print JSON-RPC

########## Create JSON-RPC response

$response = JSONRPC::response(5, null, $request->id); // create response object with result
var_dump($response);

var_dump($response->toJSONRPC()); // print JSON-RPC
// or
var_dump(JSONRPC::response(5, null, $request->id)->toJSONRPC()); // just print JSON-RPC

// in case of error

$response = JSONRPC::response(null, new JSONRPCError(JSONRPC::MethodNotFound), $request->id); // create response object with result
var_dump($response);

var_dump($response->toJSONRPC()); // print JSON-RPC

?>
