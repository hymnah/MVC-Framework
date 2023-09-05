<?php

namespace App;

use Core\Vendor\Yaml\Yaml;
use Exceptions\CriticalException;

class AppConfiguration
{
    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function getAppConfig()
    {
        $appConfigFile = '../config/app_config.yml';

        if (!file_exists($appConfigFile)) {
            return new CriticalException($appConfigFile . 'not found', 500);
        }

        $appConfig = Yaml::parse($appConfigFile);

        if (isset($appConfig['import'])) {
            $imports = $appConfig['import'];
            foreach ($imports as $import) {
                $importFile = '../config/' . $import;
                $appConfig += Yaml::parse($importFile);
            }
            unset($appConfig['import']);
        }

        return $appConfig;
    }
}