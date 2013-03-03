<?php

require_once('../include.php');

$request = JSONRPC::listen();
JSONRPC::respond(JSONRPC::response(5, null, $request->id));

?>