<?php

require_once(dirname(__FILE__) . '/jsonrpc.class.php');
require_once(dirname(__FILE__) . '/error.class.php');

/**
 * JSON-RPC Response object
 *
 * When a rpc call is made, the Server MUST reply with a Response, except for in the case of Notifications.
 * The Response is expressed as a single JSON Object.
 * Either the result member or error member MUST be included, but both members MUST NOT be included.
 *
 * @version 2012.08.14
 * @license See the included LICENSE file for more information.
 * @copyright Lukasz Krawczyk
 * @author Lukasz Krawczyk <contact@lukasz.krawczyk.eu>
 */
class JSONRPCResponse extends JSONRPCObject {

    /**************************************************************************/
    // standard JSON-RPC arguments

    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     * @var string
     */
    public $jsonrpc = '2.0';
    /**
     * This member is REQUIRED on success.
     * This member MUST NOT exist if there was an error invoking the method.
     * The value of this member is determined by the method invoked on the Server.
     * @var mixed
     */
    public $result;
    /**
     * This member is REQUIRED on error.
     * This member MUST NOT exist if there was no error triggered during invocation.
     * The value for this member MUST be an Object as defined in section 5.1.
     * @var Error
     */
    public $error;
    /**
     * This member is REQUIRED.
     * It MUST be the same as the value of the id member in the Request Object.
     * If there was an error in detecting the id in the Request object (e.g. Parse error/Invalid Request), it MUST be Null.
     * @var type
     */
    public $id;

    /**
     * @example
     * <code>
     * 10-07-2012T14:28:57
     * </code>
     * @var string
     */
    public $date;
    /**************************************************************************/

    public function __construct($result = null, JSONRPCError $error = null, $id = null) {

        $this->result = $result;
        $this->error = $error;
        $this->id = $id;
    }

    public function getErrorCode() {

        return $this->error->code;
    }

    public function getErrorMessage() {

        return $this->error->message;
    }

    public function isOK() {

        return empty($this->error);
    }

    public function isError() {

        return !$this->isOK();
    }

    /**************************************************************************/
}

?>