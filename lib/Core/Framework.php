<?php

namespace Core;

class Framework
{
    static function boot()
    {
        $loader = new \Autoloader();

        $loader->add(__DIR__);
        $loader->add(__DIR__ . '/../src/');
        $loader->add(__DIR__ . '/../lib/');

        \Core\Helper::setup();
        \Core\Session::setup();
        \Core\Request::setup();
        \App\AppRouter::setup();
        \Core\Services::setup();
        \Core\Db::setup();
    }
}