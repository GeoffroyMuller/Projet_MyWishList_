<?php
/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 26/12/2018
 * Time: 12:44
 */

namespace mywishlist\Exception;


use Throwable;

class AuthException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}