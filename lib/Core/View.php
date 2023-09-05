<?php

namespace Core;

class View
{
    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function render($fileLocation, array $params = [])
    {
        echo $this->getContent($fileLocation, $params);
        return true;
    }

    public function getContent($fileLocation, array $params = [])
    {
        ob_start();

        $pageTitle = '';
        $pageBody = '';
        $pageCss = '';
        $pageJs = '';

        foreach ($params as $key => $param) {
            ${$key} = $param;
        }

        include '../src/Views/' . $fileLocation;

        global $extends;
        if (!empty($extends)) {
            foreach ($extends as $extend) {
                include_once '../src/Views/' . $extend;
            }
        }

        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}