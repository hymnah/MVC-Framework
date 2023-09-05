<?php

namespace Core\FieldTypes;

use Core\Abstracts\FieldType;

class SelectType extends FieldType
{
    protected $type = self::_E_SELECT;

    protected $defaults = [
        'choices' => []
    ];
}