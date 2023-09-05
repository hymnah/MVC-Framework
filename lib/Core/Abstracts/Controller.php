<?php

namespace Core\Abstracts;

use Core\FormBuilder;
use Core\Logger;
use Core\Request;
use Core\Router;
use Core\Services;
use Core\View;

abstract class Controller
{
    private $request;

    private $router;

    private $logger;

    public function __construct()
    {
        $this->request = Request::getRequest();
        $this->logger = Logger::getInstance();
        $this->router = Router::getInstance();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function log($msg, $type)
    {
        $this->logger->log($msg, $type);
    }

    public function render($fileLocation, $param = [])
    {
        $view = View::getInstance();
        return $view->render($fileLocation, $param);
    }

    public function createForm($formType, $dataClass)
    {
        $formBuilder = FormBuilder::getInstance();
        return $formBuilder->createForm($formType, $dataClass);
    }

    public function get($service)
    {
        return Services::get($service);
    }
}