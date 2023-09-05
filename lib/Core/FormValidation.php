<?php

namespace Core;

class FormValidation
{
    const NOT_BLANK = 'not_blank';
    const NOT_BLANK_DEFAULT_MSG = 'Cannot be empty';
    const LEN = 'length';
    const LEN_MIN_DEFAULT = 'Cannot be shorter than {{ min }}';
    const LEN_MAX_DEFAULT = 'Cannot be longer than {{ max }}';
    const VAL = 'value';
    const VAL_MIN_DEFAULT = 'Cannot be less than {{ min }}';
    const VAL_MAX_DEFAULT = 'Cannot be greater than {{ max }}';
    const MIN = 'min';
    const MAX = 'max';

    public static function validate($class, &$errors = [])
    {
        $reflection = new \ReflectionClass($class);
        $className = $reflection->getName();
        $validations = bundle_config_parser('/config/validation.yml');
        $validationConfig = [];

        foreach ($validations as $validation) {
            foreach ($validation as $key => $eachValidation) {
                if ('Model' . $key == $className) {
                    $validationConfig = $eachValidation;
                    break 2;
                }
            }
        }

        if (empty($validationConfig)) {
            return;
        }

        $data = $class->getData();
        foreach ($data as $key => $datum) {
            $configs = $validationConfig[$key];
            foreach ($configs as $keyy => $config) {
                switch ($keyy) {
                    case self::NOT_BLANK:
                        if (empty(strlen(trim($datum)))) {
                            $blankMessage = self::NOT_BLANK_DEFAULT_MSG;
                            if (isset($config['message'])) {
                                $blankMessage = $config['message'];
                            }
                            $errors[$key] = $blankMessage;
                            continue 3;
                        }
                        break;
                    case self::LEN:
                        foreach ($config as $keyyy => $cfg) {
                            if ($keyyy == $minKey = self::MIN) {
                                if (strlen($datum) < $cfg) {
                                    $lenMinMessage = self::LEN_MIN_DEFAULT;
                                    if (isset($config[$minKey]) && isset($config['min_message'])) {
                                        $lenMinMessage = $config['min_message'];
                                    }
                                    if (stripos($lenMinMessage, $minVar = '{{ min }}') !== false) {
                                        $lenMinMessage = str_replace($minVar, $cfg, $lenMinMessage);
                                    }
                                    $errors[$key] = $lenMinMessage;
                                    continue 3;
                                }
                            }
                            if ($keyyy == $maxKey = self::MAX) {
                                if (strlen($datum) > $cfg) {
                                    $lenMaxMessage = self::LEN_MAX_DEFAULT;
                                    if (isset($config[$maxKey]) && isset($config['max_message'])) {
                                        $lenMaxMessage = $config['max_message'];
                                    }
                                    if (stripos($lenMaxMessage, $maxVar = '{{ max }}') !== false) {
                                        $lenMaxMessage = str_replace($maxVar, $cfg, $lenMaxMessage);
                                    }
                                    $errors[$key] = $lenMaxMessage;
                                    continue 3;
                                }
                            }
                        }
                        break;

                    case self::VAL:
                        foreach ($config as $keyyy => $cfg) {
                            if ($keyyy == $minKey = self::MIN) {
                                if ((is_int($datum) && is_int($cfg)) && $datum < $cfg) {
                                    $valMinMessage = self::VAL_MIN_DEFAULT;
                                    if (isset($config[$minKey]) && isset($config['min_message'])) {
                                        $valMinMessage = $config['min_message'];
                                    }
                                    if (stripos($valMinMessage, $maxVar = '{{ max }}') !== false) {
                                        $valMinMessage = str_replace($maxVar, $cfg, $valMinMessage);
                                    }
                                    $errors[$key] = $valMinMessage;
                                    continue 3;
                                }
                            }
                            if ($keyyy == $maxKey = self::MAX) {
                                if ((is_int($datum) && is_int($cfg)) && $datum > $cfg) {
                                    $valMaxMessage = self::VAL_MAX_DEFAULT;
                                    if (isset($config[$minKey]) && isset($config['min_message'])) {
                                        $valMaxMessage = $config['max_message'];
                                    }
                                    if (stripos($valMaxMessage, $maxVar = '{{ max }}') !== false) {
                                        $valMaxMessage = str_replace($maxVar, $cfg, $valMaxMessage);
                                    }
                                    $errors[$key] = $valMaxMessage;
                                    continue 3;
                                }
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }

}