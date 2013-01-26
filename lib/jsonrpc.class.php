<?php

require_once(dirname(__FILE__) . '/request.class.php');
require_once(dirname(__FILE__) . '/response.class.php');

/**
 * JSONRPC object
 *
 * @version 2012.08.14
 * @license See the included LICENSE file for more information.
 * @copyright Lukasz Krawczyk
 * @author Lukasz Krawczyk <contact@lukasz.krawczyk.eu>
 */
class JSONRPC {

    /**************************************************************************/

    /**
     * Error messages
     *
     * -32700 : Parse error - Invalid JSON was received by the server. An error occurred on the server while parsing the JSON text.
     * -32600 : Invalid Request - The JSON sent is not a valid Request object.
     * -32601 : Method not found - The method does not exist / is not available.
     * -32602 : Invalid params - Invalid method parameter(s).
     * -32603 : Internal error - Internal JSON-RPC error.
     * -32000 to -32099 : Server error - Reserved for implementation-defined server-errors.
     */
    const ParseError        = -32700;
    const InvalidRequest    = -32600;
    const MethodNotFound    = -32601;
    const InvalidParams     = -32602;
    const InternalError     = -32603;
    const ServerError       = -32000;

    /**
     * Contain text messages of all available errors
     *
     * @var mixed
     */
    public static $messages = array(
        self::ParseError        => 'Parse error',
        self::InvalidRequest    => 'Invalid Request',
        self::MethodNotFound    => 'Method not found',
        self::InvalidParams     => 'Invalid params',
        self::InternalError     => 'Internal error',
        self::ServerError       => 'Server error',
    );

    /**************************************************************************/
    // encode / decode functions

    /**
     * Convert JSONRPCRequest or JSONRPCResponse objects into JSON-RPC string
     * @param mixed $object
     * @return string
     */
    public static function encode($object) {
        // convert object to array
        $array = array();
        $array = self::objectToArray($object);

        // return json string
        return json_encode($array);
    }

    /**
     * Convert JSONRPC string into JSONRPCRequest or JSONRPCResponse object
     *
     * @param string $json
     * @return JSONRPCRequest | JSONRPCResponse
     */
    public static function decode($json) {
        // type validation
        if (is_string($json)) {
            $object = json_decode($json);
        } else {
            throw new InvalidArgumentException(__FUNCTION__.' function accepts string only.');
        }

        // cast stdClass object to JSONRPCRequest or JSONRPCResponse object
        return self::objectCast($object);
    }

    /**************************************************************************/
    // create object functions

    /**
     * Create new Request object
     *
     * @param string $method
     * @param mixed $params
     * @param int $id
     * @return JSONRPCRequest
     */
    public static function request($method = null, $params = array(), $id = null) {
        // if id is null, generate unique id for this request
        if (is_null($id))
            $id = self::uuid();

        return new JSONRPCRequest($method, $params, $id);
    }

    /**
     * Create notification request object (Request object without id)
     *
     * @param string $method
     * @param mixed $params
     * @param int $id
     * @return JSONRPCRequest
     */
    public static function notification($method = null, $params = array()) {

        return new JSONRPCRequest($method, $params);
    }

    /**
     * Create new Response object
     *
     * @param mixed $result
     * @param JSONRPCError $error
     * @param int $id
     * @return JSONRPCResponse
     */
    public static function response($result = null, JSONRPCError $error = null, $id = null) {

        return new JSONRPCResponse($result, $error, $id);
    }

    /**
     * Create new Error object
     *
     * @param int $code
     * @param string $message
     * @param mixed $data
     * @return JSONRPCError
     */
    public static function error($code = null, $message = null, $data = null) {

        return new JSONRPCError($code, $message, $data);
    }

    /**
     * Generate unique id for request
     * Version 4 UUIDs have the form xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
     * where x is any hexadecimal digit and y is one of 8, 9, A, or B.
     *
     * @example f47ac10b-58cc-4372-a567-0e02b2c3d479
     * @link http://en.wikipedia.org/wiki/Universally_unique_identifier
     * @return string A v4 uuid
     */
    static function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), // time_low
            mt_rand(0, 0xffff), // time_mid
            mt_rand(0, 0x0fff) | 0x4000, // time_hi_and_version
            mt_rand(0, 0x3fff) | 0x8000, // clk_seq_hi_res/clk_seq_low
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // node
            );
    }
    /**************************************************************************/
    // additional functions

    /**
     * Cast stdObject to one of JSON-RPC objects
     *
     * @param stdClass $object
     * @return JSONRPCRequest | JSONRPCResponse | JSONRPCError
     */
    protected static function objectCast(stdClass $object) {
        // object cast
        if (isset($object->method)) { // Request object
            $object = self::cast($object, 'JSONRPCRequest' );
        } elseif (isset($object->result) || isset($object->error)) { // Response object
            $object = self::cast($object, 'JSONRPCResponse' );
            if (isset($object->error))
                $object->error = self::cast($object->error, 'JSONRPCError' );
        }

        return $object;
    }

    /**
     * Convert any kind of object into an array and removes all null values
     *
     * @param object $object
     * @return mixed
     */
    protected static function objectToArray($object) {

        $array = json_decode(json_encode($object), true);

        return self::removeEmptyFields($array);
    }

    /**
     * Remove empty fields from an array recursively
     *
     * @param mixed $haystack
     * @return mixed
     */
    protected static function removeEmptyFields(array $haystack) {

        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = self::removeEmptyFields($haystack[$key]);
            }
            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }


    /**
     * Cast stdClass object to another class instance
     * IMPORTANT - stdClass should contain same members as given class
     *
     * @param object $object
     * @param string $className
     * @return object
     */
    protected static function cast(stdClass $object, $className) {

        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($object), '"'), ':')
        ));
    }

    /**************************************************************************/
}

/**
 * Represent every json-rpc object - Response, Request and Error
 */
class JSONRPCObject {

    /**************************************************************************/

    /**
     * Conversion to JSONRPC
     *
     * @return string
     */
    public function toJSONRPC() {

        return JSONRPC::encode($this);
    }

    /**
     * Conversion to JSONRPC
     *
     * @return string
     */
    public function __toString() {

        return $this->toJSONRPC();
    }

    /**************************************************************************/
}

?>