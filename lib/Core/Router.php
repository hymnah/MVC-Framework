<?php

namespace Core;

use Core\Utilities\RouteFinder;
use Exceptions\CriticalException;

class Router
{
    private $routes;

    private function __construct()
    {
        $this->routes = self::getAppRoutes();
    }

    public static function getInstance()
    {
        return new self();
    }

    public function generateRoute($route, $attr = [])
    {
        $matchingKey = '';
        $matchingBundle = '';
        $generatedRouteArr = [];

        try {
            foreach ($this->routes as $bundle => $eachRoute) {
                foreach ($eachRoute as $key => $eachRouteCfg) {
                    if ($key == $route) {
                        $matchingBundle = $bundle;
                        $matchingKey = $key;
                    }
                }
            }

            if (empty($matchingKey)) {
                throw new CriticalException('"' . $route . '" is not configured');
            }

            $matchingRoute = $this->routes[$matchingBundle][$matchingKey]['route'];
            $matchingRoute = trim($matchingRoute, '/');
            $routeSegments = explode('/', $matchingRoute);

            foreach ($routeSegments as $routeSegment) {
                if (is_pattern_parameter($routeSegment)) {
                    $paramSegment = explode(':', trim($routeSegment, '{}'));
                    if (!isset($attr[$param = $paramSegment[0]])) {
                        throw new CriticalException('Missing ' . $param . ' attribute to generate url');
                    }

                    $generatedRouteArr[] = $attr[$param = $paramSegment[0]];
                    continue;
                }

                $generatedRouteArr[] = $routeSegment;
            }
        } catch (\Exception $e) {

        }

        return '/' . trim(implode('/', $generatedRouteArr), '/');
    }

    public static function redirectToRoute($route)
    {
        $routeFinder = new RouteFinder($route);
        $matchedRoute = $routeFinder->getRoute();
        $uri = new URI();

        if ($uri->getUrl() !== $matchedRoute) {
            header('location: ' . $matchedRoute);
        }

        return $routeFinder;
    }

    public static function redirect($route)
    {
        $uri = new URI();
        if (trim($uri->getUrl(), '/') !== trim($route, '/')) {
            header('location: ' . $route);
        }
    }

    public static function getAppRoutes()
    {
        $routeConfigFile = 'config/routing.yml';
        return bundle_config_parser($routeConfigFile);
    }
}