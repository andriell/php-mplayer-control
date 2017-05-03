<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 03.05.2017
 * Time: 10:44
 */

namespace App\Lib\Transmission;


class RPCException extends \Exception
{
    /**
     * Exception: Invalid arguments
     */
    const E_INVALIDARG = -1;

    /**
     * Exception: Invalid Session-Id
     */
    const E_SESSIONID = -2;

    /**
     * Exception: Error while connecting
     */
    const E_CONNECTION = -3;

    /**
     * Exception: Error 401 returned, unauthorized
     */
    const E_AUTHENTICATION = -4;

    /**
     * Exception constructor
     * @param null $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
         parent::__construct($message, $code, $previous);
    }
}
