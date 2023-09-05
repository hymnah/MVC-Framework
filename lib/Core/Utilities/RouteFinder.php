<?php

namespace Core\Utilities;

use Exceptions\NotFoundException;

class RouteFinder
{
    private $route;

    private $routeConfigFile;

    public function __construct($route)
    {
        $this->route = $route;
        $this->routeConfigFile = 'config/routing.yml';
    }

    private function getConfig()
    {
        $appRoutes = bundle_config_parser($this->routeConfigFile);
        $foundRoute = '';

        foreach ($appRoutes as $appRoute) {
            foreach ($appRoute as $key => $eachRoute) {
                if ($key === $this->route) {
                    $foundRoute = $eachRoute;
                }
            }
        }

        if (empty($foundRoute)) {
            throw new \Exception('Route ' . $this->route . ' is not configured');
        }

        return $foundRoute;
    }

    public function getRoute()
    {
        return $this->getConfig()['route'];
    }

    public function getController()
    {
        return $this->getConfig()['controller'];
    }

    public function getMethod()
    {
        return $this->getConfig()['method'];
    }
}