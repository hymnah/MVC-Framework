<?php

namespace Core;

class Helper
{
    public static function setup()
    {
        require_once('../lib/Helpers/dumper.php');
        require_once('../lib/Helpers/route.php');
        require_once('../lib/Helpers/form.php');
        require_once('../lib/Helpers/blocks.php');
        require_once('../lib/Helpers/function.php');
    }
}