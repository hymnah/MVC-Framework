<?php

namespace Core\Abstracts;

use Core\Interfaces\Model;
use Core\Interfaces\FormType as FormTypeInterface;
use Core\URI;
use Core\View;
use Exceptions\FormBuilderException;

abstract class FormType implements FormTypeInterface
{
    protected $dataClass;

    protected $fields;

    private $errors = [];

    public function build()
    {
        static::buildForm();

        $dataClass = $this->dataClass;
        $fields = $this->fields;
        $fillable = $dataClass->getFillable();

        $formElements = [];
        foreach ($fields as $key => $field) {
            if (!in_array($key, $fillable)) {
                throw new FormBuilderException('Invalid field ' . $key . ' for ' . static::class . ' form');
            }

            foreach ($field as $key2 => $eachField) {
                if ($key2 === 'type') {
                    $fieldType = new $field['type']();
                    $value = isset($dataClass->getData()[$key]) ? $dataClass->getData()[$key] : '';
                    $options = [
                        'attr' => array_merge($field['attr'], ['id' => $key, 'name' => $key, 'value' => $value]),
                        'choices' => isset($field['choices']) ? $field['choices'] : []
                    ];
                    $fieldType->setOptions($options);
                    if (isset($this->errors[$key])) {
                        $fieldType->setError($this->errors[$key]);
                    }

                    $formElements[$key] = $fieldType->getDisplay();
                }
            }

            $formElements[$key . ':error'] = '';
        }

        $view = View::getInstance();

        $defaults = $this->getDefaults();
        $attr = [];
        foreach ($defaults as $key => $default) {
            $attr[$key] = $key . '="' . $default . '" ';
        }

        $formStart = htmlspecialchars($view->getContent('Globals/form/form-start.php', ['attr' => implode('', $attr)]));
        $formEnd = htmlspecialchars($view->getContent('Globals/form/form-end.php'));
        $formElements['form_start'] = $formStart;
        $formElements['form_end'] = $formEnd;

        foreach ($this->errors as $key => $error) {
            $formElements[$key . ':error'] = $error;
        }

        return $formElements;
    }

    public function addField($field, $type, $options = [])
    {
        $this->fields[$field]['type'] = $type;
        $this->fields[$field]['attr'] = $options['attr'];
        if (isset($options['choices'])) {
            $this->fields[$field]['choices'] = $options['choices'];
        }
        return $this;
    }

    public function setDataClass(Model $dataClass)
    {
        $this->dataClass = $dataClass;
    }


    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    protected function getDefaults()
    {
        return [
            'action' => URI::getInstance()->getUrl(),
            'method' => 'post'
        ];
    }
}