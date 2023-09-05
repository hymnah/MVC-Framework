<?php

namespace Core\Interfaces;

use \Core\Interfaces\Model;

interface FormType
{
    public function build();

    public function addField($field, $type, $attr = []);

    public function setDataClass(Model $dataClass);
}