<?php

namespace Core\FieldTypes;

use Core\Abstracts\FieldType;

class InputType extends FieldType
{
    protected $type = self::_E_INPUT;

    protected $defaults = [
        'type' => 'text'
    ];
}