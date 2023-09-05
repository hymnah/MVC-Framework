<?php

namespace Exceptions;

use Core\Logger;
use Core\View;

class ExceptionHandler extends \Exception
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $logger = Logger::getInstance();
        $logger->log($message . "\n" . self::getTraceAsString(), 'error');
        dump($message . "\n" . self::getTraceAsString());

        $view = new View();
        $view->render('Errors/' . $code . '.html');
        exit;
    }
}