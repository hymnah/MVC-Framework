<?php

/**
 * Date: 6/30/2022
 *
 * Project Name: PHP Custom framework
 * Developer: Albert Igcasan
 * Project Description: Simple PHP framework implements MVC structure
 *
**/

class Framework
{
    static function boot()
    {
        require_once('Autoloader.php');
        $loader = new Autoloader();

        $loader->add(__DIR__);
        $loader->add(__DIR__ . '/../src/');
        $loader->add(__DIR__ . '/../lib/');

        \Core\Helper::setup();
        \Core\Session::setup();
        \Core\Request::setup();
        \Core\Services::setup();
        \Core\Db::setup();
        \App\AppRouter::setup();
    }
}

Framework::boot();

?>
