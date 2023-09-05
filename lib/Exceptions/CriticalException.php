<?php

namespace Exceptions;

class CriticalException extends ExceptionHandler
{
    public function __construct($message = "", $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}