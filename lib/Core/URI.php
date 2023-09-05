<?php

namespace Core;

class URI
{
    private $url;

    private $queryString;

    private static $_instance;

    public function __construct()
    {
        $this->url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        $this->queryString = str_replace('?', '', $_SERVER['QUERY_STRING']);
    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @return string|string[]
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string|string[]
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

}