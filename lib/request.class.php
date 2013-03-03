<?php

require_once(dirname(__FILE__) . '/jsonrpc.class.php');

/**
 * JSON-RPC Request object
 *
 * A rpc call is represented by sending a Request object to a Server.
 * The Server MUST reply with the same value in the Response object if included.
 * This member is used to correlate the context between the two objects.
 *
 * @version 2012.08.14
 * @license See the included LICENSE file for more information.
 * @copyright Lukasz Krawczyk
 * @author Lukasz Krawczyk <contact@lukaszkrawczyk.eu>
 */
class JSONRPCRequest extends JSONRPCObject {

    /**************************************************************************/
    // standard JSON-RPC arguments

    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     * @var string
     */
    public $jsonrpc = '2.0';
    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period character (U+002E or ASCII 46)
     * are reserved for rpc-internal methods and extensions and MUST NOT be used for anything else.
     * @var string
     */
    public $method;
    /**
     * A Structured value that holds the parameter values to be used during the invocation of the method. This member MAY be omitted.
     * @var mixed
     */
    public $params;
    /**
     * An identifier established by the Client that MUST contain a String, Number, or NULL value if included.
     * If it is not included it is assumed to be a notification.
     * The value SHOULD normally not be Null [1] and Numbers SHOULD NOT contain fractional parts [2].
     *
     * [1] The use of Null as a value for the id member in a Request object is discouraged,
     * because this specification uses a value of Null for Responses with an unknown id.
     * Also, because JSON-RPC 1.0 uses an id value of Null for Notifications this could cause confusion in handling.
     * [2] Fractional parts may be problematic, since many decimal fractions cannot be represented exactly as binary fractions.
     * @var type
     */
    public $id;

    /**************************************************************************/

    /**
     * @param type $method
     * @param type $params
     * @param type $id
     */
    public function __construct($method = null, $params = array(), $id = null) {

        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * @param string $jsonrpc
     * @return JSONRPCRequest
     */
    public function setJsonrpc($jsonrpc = null) {

        $this->jsonrpc = $jsonrpc;

        return $this;
    }

    /**
     * @param string $method
     * @return JSONRPCRequest
     */
    public function setMethod($method = null) {

        $this->method = $method;

        return $this;
    }

    /**
     * @param mixed $params
     * @return JSONRPCRequest
     */
    public function setParams($params = array()) {

        $this->params = $params;

        return $this;
    }

    /**
     * @param int $id
     * @return JSONRPCRequest
     */
    public function setId($id = null) {

        $this->id = $id;

        return $this;
    }

    /**************************************************************************/
}

?>