<?php

namespace App;

use Core\Logger;
use Core\Request;
use Core\Router;
use Core\Session;
use Core\URI;
use Core\Vendor\Yaml\Yaml;
use Exceptions\NotFoundException;

class AppRouter
{
    private static $appConfig;

    private static $appRoutes;

    public static function setup()
    {
        self::$appConfig = AppConfiguration::getAppConfig();
        self::$appRoutes = Router::getAppRoutes();

        try {
            $routes = self::$appRoutes;
            $bundleMatch = '';
            $routeMatch = '';
            self::routeMatcher($bundleMatch, $routeMatch);

            if (empty($bundleMatch) || empty($routeMatch)) {
                throw new NotFoundException('Route not found');
            }

            $controller = $routes[$bundleMatch][$routeMatch]['controller'];
            if (!class_exists($controllerClass = 'Controllers\\' . $bundleMatch . '\\' . $controller . self::$appConfig['controller_suffix'])) {
                throw new NotFoundException($controllerClass . ' does not exist');
            }

            $method = $routes[$bundleMatch][$routeMatch]['method'] . self::$appConfig['method_suffix'];
            if (!method_exists($controllerClass, $method)) {
                throw new NotFoundException('Method ' . $method . ' of class ' . $controllerClass . ' does not exist');
            }

            Logger::getInstance()->log('', 'debug');

        } catch (\Exception $e) {
            return;
        }


        /***
         * Controller method parameter auto-wiring
        */
        $reflection = new \ReflectionClass($controllerClass);
        $methods = $reflection->getMethods();
        $controllerClass = new $controllerClass;
        foreach ($methods as $key => $methodObj) {
            if ($methodObj->getName() == $method) {
                $methodClasses = [];
                $methodParams = $reflection->getMethod($methodObj->getName())->getParameters();
                foreach ($methodParams as $methodParam) {
                    if ($methodParamClass = $methodParam->getClass()) {
                        $methodClassName = $methodParamClass->getName();
                        $methodClasses[] = new $methodClassName();
                    }
                }

                if (!empty($methodClasses)) {
                    call_user_func_array([$controllerClass, $methodObj->getName()], $methodClasses);
                    continue;
                }

                $controllerClass->{$methodObj->getName()}();
            }
        }
    }

    private static function routeMatcher(&$bundleMatch, &$routeMatch)
    {
        $url = Request::getRoute();

        foreach (self::$appRoutes as $bundleIdx => $bundle) {
            foreach ($bundle as $routeIdx => $route) {
                $cfgRoute = trim($route['route'], '/');
                $urlRoute = trim($url, '/');

                $cfgRouteArr = explode('/', $cfgRoute);
                $urlRouteArr = explode('/', $urlRoute);

                $routeFound = true;
                $getParams = [];

                if (count($urlRouteArr) > count($cfgRouteArr)) {
                    continue;
                }

                foreach ($cfgRouteArr as $key => $cfg) {

                    if (!isset($urlRouteArr[$key])) {
                        $routeFound = false;
                        break;
                    }

                    $isPattern = is_pattern_parameter($cfg);

                    if ($cfg !== $urlRouteArr[$key] && !$isPattern) {
                        $routeFound = false;
                        break;
                    }

                    if ($isPattern) {
                        $getParam = explode(':', trim($cfg, '{}'));
                        $getParams[$getParam[0]] = $urlRouteArr[$key];
                        if (isset($getParam[1]) && !preg_match('/' . $getParam[1] . '/', $urlRouteArr[$key])) {
                            throw new NotFoundException('Parameter did\'nt match ' . $getParam[1] . ' pattern');
                        }
                        continue;
                    }
                }

                if ($routeFound) {
                    $bundleMatch = $bundleIdx;
                    $routeMatch = $routeIdx;

                    foreach ($getParams as $key => $getParamVal) {
                        Request::addGetData($key, $getParamVal);
                    }
                }
            }
        }
    }

    private static function checkSession()
    {
        $securityConfigFile = '../config/security.yml';
        $mainCfg = Yaml::parse($securityConfigFile)['security']['main'];
        $httpResponseCode = http_response_code();
        $hasHttpError = $httpResponseCode > 404 && $httpResponseCode < 200;
        $indexUrl = trim((new URI())->getUrl(), '/');

        try {
            if (null === Session::getSession('admin') && !$hasHttpError && !empty($indexUrl)) {
                Router::redirect($mainCfg['route']);
                return false;
            }
        } catch (\Exception $e) {

        }

        return true;
    }
}