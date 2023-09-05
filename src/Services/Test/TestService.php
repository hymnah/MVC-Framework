<?php

namespace Services\Test;

use Core\Request;

class TestService
{
    public $sample;

    public function __construct(Request $request, $aaa)
    {
        dump($aaa);die;
//        dump($request::getRoute());die;
    }

    public function sample()
    {
        echo 'afdafaf';
    }
}