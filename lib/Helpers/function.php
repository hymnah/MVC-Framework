<?php

use Exceptions\NotFoundException;
use Core\Vendor\Yaml\Yaml;

function camel_to_snake($str, $pattern = '/[A-Z \d]/')
{
    return preg_replace_callback($pattern, function ($match) {
        return '_' . strtolower($match[0]);
    }, $str);
}

function snake_to_camel($str, $separator = '_')
{
    return str_replace($separator, '', ucwords($str, $separator));
}

function bundle_config_parser($configFile)
{
    $basePath = __DIR__ . '/../../src';
    $path = $basePath . '/Controllers';
    $configData = [];

    try {
        $bundles = array_diff(scandir($path), array('.', '..'));
        foreach ($bundles as $bundle) {
            if (file_exists($cfgFile = $path . '/' . $bundle . '/' . $configFile)) {
                $configData[$bundle] = Yaml::parse($cfgFile);
            }
        }

        if (empty($configData)) {
            throw new NotFoundException();
        }
    } catch (\Exception $e) {

    }

    return $configData;
}