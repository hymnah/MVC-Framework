<?php

namespace Core;

use App\AppConfiguration;
use PDO;

class Db
{
    private static $host;

    private static $dbname;

    public static $username;

    private static $password;

    private static $hasActiveTransaction = false;

    private static $_conn;

    private static $_instance;

    private function __construct() {}

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function setup()
    {
        $appConfig = AppConfiguration::getAppConfig();
        $db = $appConfig['database'];
        self::$host = $db['server'];
        self::$dbname = $db['name'];
        self::$username = $db['user'];
        self::$password = $db['password'];

        self::connect();
    }

    public static function connect()
    {
        $conn = null;

        try {
            $conn = new PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname, self::$username, self::$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            dump($e->getMessage());die;
        }

        self::$_conn = $conn;
    }

    public static function getConn()
    {
        return self::$_conn;
    }

    public static function beginTransaction()
    {
        if (self::$hasActiveTransaction) {
            return false;
        } else {
            self::$hasActiveTransaction = self::getConn()->beginTransaction ();
            return self::$hasActiveTransaction;
        }
    }

    public static function commit()
    {
        self::getConn()->commit();
        self::$hasActiveTransaction = false;
        self::$_conn = null;
    }

    public static function rollback () {
        self::getConn()->rollback ();
        self::$hasActiveTransaction = false;
        self::$_conn = null;
    }
}