<?php

namespace Core;

class Session
{
    private static $session;

    public static function setup()
    {
        self::startSession();
        self::$session = $_SESSION;
    }

    private static function startSession()
    {
        session_start();
    }

    public static function getSession($key)
    {
        if (!isset(self::$session[$key]))
            return null;
        return self::$session[$key];
    }

    public static function setSession($key, $value)
    {
        self::$session[$key] = $value;
    }
}