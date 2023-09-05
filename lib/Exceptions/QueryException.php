<?php

namespace Exceptions;

class QueryException extends ExceptionHandler
{
    public function __construct($message = "", $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}