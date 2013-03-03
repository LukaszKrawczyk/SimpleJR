# SimpleJR #
JSON-RPC library for PHP

SimpleJR IS:
- simple library providing basic methods to send and receive JSON-RPC requests

SimpleJR IS NOT:
- fully featured Client-Server RPC library

Learn more about JSON-RPC at <http://jsonrpc.org/>.

## Requirements ##

* PHP 5 >= 5.2.0
* PECL json >= 1.2.0

## Installation ##

```php
require_once('SimpleJR/include.php');
```

## Examples ##

Create JSON-RPC request
```php
// simplest JSONRPC request
$request = JSONRPC::request('math.add', array(1, 4));
echo $request; // print JSON-RPC

// - OR -
echo JSONRPC::request('math.add', array(1, 4));

// OR create JSON-RPC request with named parameters
$request = JSONRPC::request();
$request->method = 'users.getUser';
$request->params = array('firstName' => 'Jan', 'lastName' => 'Nowak');
echo $request; // print JSON-RPC
```

Create JSON-RPC response
```php
$response = JSONRPC::response(5, null, $request->id); // create response object with result
echo $response; // print JSON-RPC

// - OR -
echo JSONRPC::response(5, null, $request->id); // just print JSON-RPC
```

Create JSON-RPC error response
```php
// JSONRPC response in one line
$response = JSONRPC::response(null, JSONRPC::error(JSONRPC::MethodNotFound), $request->id);
echo $response; // print JSON-RPC

// - OR -
$response = JSONRPC::response();
$response->result = null;
$response->error = JSONRPC::error(JSONRPC::MethodNotFound);
$response->id = $request->id);
echo $response;
```

Decode JSON-RPC request
```php
$jsonrpc = '{"jsonrpc":"2.0","method":"add","params":[1,4],"id":1}';
$request = JSONRPC::decode($jsonrpc);
echo $request->method;
```

Decode JSON-RPC response
```php
$jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
$response = JSONRPC::decode($jsonrpc);

if ($response->isOK()) echo $request->result;
```

Decode JSON-RPC error response
```php
$jsonrpc = '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":1}';
$response = JSONRPC::decode($jsonrpc);

if (!$response->isOK()) echo $request->error->message;
```

## Sample client-server ##

Sending JSON-RPC request
```php
$request = JSONRPC::request('math.add', array(1, 4)); // create request object
JSONRPC::send($request, $url); // send request to server
```

Receiving and responding to JSON-RPC request
```php
$request = JSONRPC::listen(); // listen to request
$response = $response = JSONRPC::response(5, null, $request->id); // create response
JSONRPC::respond($response); // respond to request
```