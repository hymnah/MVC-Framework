<?php

namespace Exceptions;

class NotFoundException extends ExceptionHandler
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}