# SimpleJR #
JSON-RPC library for PHP

SimpleJR IS:
- simple library providing basic methods to create JSON-RPC response and request

SimpleJR IS NOT:
- fully featured Client-Server RPC library

Learn more about JSON-RPC at <http://jsonrpc.org/>.

## Examples ##

Create JSON-RPC request
```php
<?php

$request = JSONRPC::request('math.add', array(1, 4)); // create request object
echo $request->toJSONRPC(); // print JSON-RPC
// or
echo JSONRPC::request('math.add', array(1, 4))->toJSONRPC(); // just print JSON-RPC

// Or create JSON-RPC request with named parameters

$request = JSONRPC::request();
$request->method = 'users.getUser';
$request->params = array('firstName' => 'Jan', 'lastName' => 'Nowak');
echo $request->toJSONRPC(); // print JSON-RPC

?>
```

Create JSON-RPC response
```php
<?php

$response = JSONRPC::response(5, null, $request->id); // create response object with result
echo $response->toJSONRPC(); // print JSON-RPC
// or
echo JSONRPC::response(5, null, $request->id)->toJSONRPC(); // just print JSON-RPC

?>
```

Create JSON-RPC error response
```php
<?php

$response = JSONRPC::response(null, new JSONRPCError(JSONRPC::MethodNotFound), $request->id); // create response object with result
echo $response->toJSONRPC(); // print JSON-RPC

?>
```

Decode JSON-RPC request
```php
<?php

$jsonrpc = '{"jsonrpc":"2.0","method":"add","params":[1,4],"id":1}';
$request = JSONRPC::decode($jsonrpc);
echo $request->method;

?>
```

Decode JSON-RPC response
```php
<?php

$jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
$response = JSONRPC::decode($jsonrpc);

if ($response->isOK())
    echo $request->result;

?>
```

Decode JSON-RPC error response
```php
<?php

$jsonrpc = '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":1}';
$response = JSONRPC::decode($jsonrpc);

if (!$response->isOK())
    echo $request->error->message;

?>
```