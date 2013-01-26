<?php

/**
 * JSON-RPC Error object
 *
 * When a rpc call encounters an error, the Response Object MUST contain the error member
 * with a value that is a Object.
 *
 * The error codes from and including -32768 to -32000 are reserved for pre-defined errors.
 * Any code within this range, but not defined explicitly below is reserved for future use.
 * The error codes are nearly the same as those suggested for XML-RPC at the following
 * url: http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php
 *
 * -32700   Parse error         Invalid JSON was received by the server. An error occurred on the server while parsing the JSON text.
 * -32600 	Invalid Request 	The JSON sent is not a valid Request object.
 * -32601 	Method not found 	The method does not exist / is not available.
 * -32602 	Invalid params      Invalid method parameter(s).
 * -32603 	Internal error      Internal JSON-RPC error.
 * -32000 to -32099 	Server error 	Reserved for implementation-defined server-errors.
 *
 * @version 2012.08.14
 * @license See the included LICENSE file for more information.
 * @copyright Lukasz Krawczyk
 * @author Lukasz Krawczyk <contact@lukasz.krawczyk.eu>
 */
class JSONRPCError extends JSONRPCObject {

    /**************************************************************************/

    /**
     * A Number that indicates the error type that occurred.
     * This MUST be an integer.
     * @var int
     */
    public $code;
    /**
     * A String providing a short description of the error.
     * The message SHOULD be limited to a concise single sentence.
     * @var string
     */
    public $message;
    /**
     * A Primitive or Structured value that contains additional information about the error.
     * This may be omitted.
     * The value of this member is defined by the Server (e.g. detailed error information, nested errors etc.).
     * @var mixed
     */
    public $data;

    /**************************************************************************/

    /**
     *
     * @param int $code
     * @param string $message
     * @param mixed $data
     */
    public function __construct($code = null, $message = null, $data = null) {
        // validate and set code
        if (!is_int($code)) {
            throw new InvalidArgumentException(__FUNCTION__.' function accepts integer as code number only.');
        }
        $this->code = $code;

        // set message
        if (!empty($message))
            $this->message = $message;
        elseif( !empty(JSONRPC::$messages[$code]) )
            $this->message = JSONRPC::$messages[$code];

        // set data
        $this->data = $data;
    }

    /**************************************************************************/
}

?>