<?php

namespace Controllers\TestBundle\forms;

use Core\Abstracts\FormType;
use Core\FieldTypes\InputType;
use Core\FieldTypes\SelectType;
use Core\FieldTypes\TextareaType;

class TestFormType extends FormType
{
    public function buildForm()
    {
        $this
            ->addField('test_field', InputType::class, [
                'attr' => [
                    'class' => 'zzz',
                    'data-zzz' => 'adadadad'
                ]
            ])
            ->addField('test_field_2', SelectType::class, [
                'choices' => [
                    'Philippines' => 'ph',
                    'Singapore' => 'sg'
                ],
                'attr' => [
                    'class' => 'www'
                ]
            ]);

        return $this->fields;
    }
}