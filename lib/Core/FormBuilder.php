<?php

namespace Core;

class FormBuilder
{
    private $display;

    private $dataClass;

    private $isSubmitted;

    private $formType;

    public static function getInstance()
    {
        return new self();
    }

    public function createForm($formType, $dataClass)
    {
        $formTypeClass = new $formType();
        $formTypeClass->setDataClass($dataClass);
        $this->display = $formTypeClass->build();
        $this->dataClass = $dataClass;
        $this->formType = $formTypeClass;
        return $this;
    }

    public function createView()
    {
        return $this->display;
    }

    public function handleRequest(Request $request)
    {
        if (empty($postRaw = $request->postData())) {
            return true;
        }

        $dataClass = $this->dataClass;
        $fillables = $dataClass->getFillable();

        $postData = [];
        foreach ($postRaw as $key => $data) {
            if (in_array($key, $fillables)) {
                $postData[$key] = $data;
            }
        }

        foreach ($fillables as $fillable) {
            $dataClass->{'set' . snake_to_camel($fillable)}($postData[$fillable]);
        }

        $this->isSubmitted = true;
        return $dataClass;
    }

    public function isSubmitted()
    {
        return $this->isSubmitted;
    }

    public function isValid()
    {
        FormValidation::validate($this->dataClass, $errors);

        if (empty($errors)) {
            return true;
        }

        $this->formType->setErrors($errors);
        $this->display = $this->formType->build();
        return false;
    }
}