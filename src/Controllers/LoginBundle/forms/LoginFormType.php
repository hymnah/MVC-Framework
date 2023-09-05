<?php

namespace Controllers\LoginBundle\forms;

use Core\Abstracts\FormType;
use Core\FieldTypes\InputType;

class LoginFormType extends FormType
{
    public function buildForm()
    {
        $this
            ->addField('username', InputType::class, [
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Username',
                ]
            ])
            ->addField('password', InputType::class, [
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Password',
                    'type' => 'password'
                ]
            ]);

        return $this->fields;
    }
}