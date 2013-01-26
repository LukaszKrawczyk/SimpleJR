<?php

require_once dirname(__FILE__) . '/../lib/jsonrpc.class.php';

/**
 * Test class for JSONRPC.
 */
class JSONRPCTest extends PHPUnit_Framework_TestCase {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers JSONRPC::encode
     */
    public function testEncode() {

        // encode request
        $request = JSONRPC::request('math.add', array(1, 4), 1);
        $jsonrpc = '{"jsonrpc":"2.0","method":"math.add","params":[1,4],"id":1}';
        $this->assertEquals(JSONRPC::encode($request), $jsonrpc);

        // encode response
        $response = JSONRPC::response(5, null, 1);
        $jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
        $this->assertEquals(JSONRPC::encode($response), $jsonrpc);
    }

    /**
     * @covers JSONRPC::decode
     */
    public function testDecode() {

        // decode JSON-RPC request
        $jsonrpc = '{"jsonrpc":"2.0","method":"add","params":[1,4],"id":1}';
        $request = JSONRPC::decode($jsonrpc);
        $this->assertEquals(get_class($request), 'JSONRPCRequest');
        $this->assertEquals($request->method, 'add');
        $this->assertEquals($request->params, array(1, 4));

        // decode JSON-RPC response
        $jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
        $response = JSONRPC::decode($jsonrpc);
        $this->assertEquals(get_class($response), 'JSONRPCResponse');
        $this->assertTrue($response->isOK());
        $this->assertEquals($response->result, 5);

        // decode JSON-RPC error response
        $jsonrpc = '{"jsonrpc":"2.0","error":{"code":-32601,"message":"Method not found"},"id":1}';
        $response = JSONRPC::decode($jsonrpc);
        $this->assertEquals(get_class($response), 'JSONRPCResponse');
        $this->assertFalse($response->isOK());
        $this->assertEquals($response->error->code, -32601);
        $this->assertEquals($response->error->message, 'Method not found');
    }

    /**
     * @covers JSONRPC::request
     */
    public function testRequest() {

        $jsonrpc = '{"jsonrpc":"2.0","method":"math.add","params":[1,4],"id":1}';
        $request = JSONRPC::request('math.add', array(1, 4), 1);
        $this->assertEquals($request->toJSONRPC(), $jsonrpc);
    }

    /**
     * @covers JSONRPC::notification
     */
    public function testNotification() {

        $jsonrpc = '{"jsonrpc":"2.0","method":"system.sayHello","params":["Hello!"]}';
        $request = JSONRPC::notification('system.sayHello', array('Hello!'));
        $this->assertEquals($request->toJSONRPC(), $jsonrpc);
    }

    /**
     * @covers JSONRPC::response
     */
    public function testResponse() {

        $jsonrpc = '{"jsonrpc":"2.0","result":5,"id":1}';
        $response = JSONRPC::response(5, null, 1);
        $this->assertEquals($response->toJSONRPC(), $jsonrpc);
    }

    /**
     * @covers JSONRPC::error
     */
    public function testError() {

        $jsonrpc = '{"code":-32601,"message":"Method not found"}';
        $error = JSONRPC::error(JSONRPC::MethodNotFound);
        $this->assertEquals($error->toJSONRPC(), $jsonrpc);

        $jsonrpc = '{"code":101,"message":"My custom message"}';
        $error = JSONRPC::error(101, 'My custom message');
        $this->assertEquals($error->toJSONRPC(), $jsonrpc);
    }

    /**
     * @covers JSONRPC::uuid
     */
    public function testUuid() {

        $uuid = JSONRPC::uuid();
        $this->assertEquals(strlen($uuid), 36);
        $this->assertEquals($uuid[14], '4');
    }

}

?>
