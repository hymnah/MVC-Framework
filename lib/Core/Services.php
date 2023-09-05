<?php

namespace Core;

use App\AppConfiguration;
use Exceptions\CriticalException;

class Services
{
    public static $services;

    public static function setup()
    {
        self::register();
    }

    public static function get($serviceName)
    {
        $thisService = self::$services[$serviceName];
        return new $thisService['class'](...$thisService['args']);
    }

    public static function register()
    {
        $services = self::getAppServices();
        foreach ($services as $bundles) {
            foreach ($bundles as $serviceKey => $service) {
                $serviceClass = 'Services\\' . $service['src'];
                $refClass = new \ReflectionClass($serviceClass);
                $classParams = $refClass->getConstructor()->getParameters();
                $newInstanceArgs = [];

                foreach ($classParams as $key => $classParam) {
                    $isServiceAutoWire = !isset($service['args']);
                    if (!$isServiceAutoWire && !isset($service['args'][$key])) {
                        throw new CriticalException('Number of declared parameter doesn\'t match the injected');
                    }

                    if (!isset($service['args'][$key])) {
                        $cfgName = $classParam->getName();
                    } else {
                        $cfgName = $service['args'][$key];
                    }

                    $cfgType = gettype($cfgName);
                    if (is_null($classParam->getType())) {
                        $injType = gettype($classParam->getName());
                    } else {
                        $injType = $classParam->getType()->getName();
                    }

                    if (!$isServiceAutoWire && $cfgName != 'auto' && ($cfgType !== $injType)) {
                        throw new CriticalException('Declared parameter doesn\'t match the injected');
                    }

                    if (!empty($classParam->getType())) {
                        $newArg = $classParam->getType()->getName();
                        $newInstanceArgs[] = new ($newArg)();
                        continue;
                    }

                    if (self::matchesConfig($cfgName)) {
                        $cfgName = self::findConfig($cfgName);
                    }

                    $newInstanceArgs[] = $cfgName;
                }

                self::$services[$serviceKey]['class'] = $serviceClass;
                self::$services[$serviceKey]['args'] = $newInstanceArgs;
            }
        }
    }

    private static function matchesConfig($val)
    {
        $startsWith = (substr($val, 0, 1) == '%');
        $strLen = strlen($val);
        $endsWith = (substr($val, $strLen - 1, $strLen) == '%');

        return $startsWith && $endsWith;
    }

    private static function findConfig($val)
    {
        $val = rtrim(ltrim($val, '%'), '%');
        if (!isset(AppConfiguration::getAppConfig()[$val]) || empty($cfg = AppConfiguration::getAppConfig()[$val])) {
            throw new CriticalException('Unable to find ' . $val . ' in app config');
        }

        return $cfg;
    }

    public static function getAppServices()
    {
        $routeConfigFile = 'config/services.yml';
        return bundle_config_parser($routeConfigFile);
    }
}