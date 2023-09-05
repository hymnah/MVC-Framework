<?php

namespace Core;

use App\AppRouter;

class Request
{
    private static $url;

    private static $route;

    private static $segments;

    private static $class;

    private static $method;

    private static $getData = [];

    private static $postData = [];

    public function __construct() {}

    public static function getRequest()
    {
        return new self;
    }

    public static function setup()
    {
        self::$url = URI::getInstance();
        self::$route = self::$url->getUrl();
        self::parseUrl();
    }

    private static function parseUrl()
    {
        $url = ltrim(self::$route, '/');
        $url = rtrim($url, '/');
        self::$segments = explode('/', $url);

        self::$class = isset(self::$segments[0]) && !empty(self::$segments[0]) ? self::$segments[0] : '';
        self::$method = isset(self::$segments[1]) && !empty(self::$segments[1]) ? self::$segments[1] : '';

        self::parseData();
    }

    public static function addGetData($key, $value)
    {
        self::$getData[$key] = $value;
    }


    private static function parseData()
    {
        if (!empty($queryStr = self::$url->getQueryString())) {
            $getData = explode('&', self::$url->getQueryString());
            foreach ($getData as $getDatum) {
                $eachGetData = explode('=', $getDatum);
                self::$getData[$eachGetData[0]] = $eachGetData[1];
            }
        }

        foreach ($_POST as $key => $post) {
            self::$postData[$key] = $post;
        }
    }

    /**
     * @return mixed
     */
    public static function getUrl()
    {
        return self::$url;
    }

    /**
     * @return mixed
     */
    public static function getRoute()
    {
        return self::$route;
    }

    /**
     * @return mixed
     */
    public static function getSegments()
    {
        return self::$segments;
    }

    /**
     * @return mixed
     */
    public static function getClass()
    {
        return self::$class;
    }

    /**
     * @return mixed
     */
    public static function getMethod()
    {
        return self::$method;
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return self::$getData;
    }

    /**
     * @return array
     */
    public static function postData()
    {
        return self::$postData;
    }
}