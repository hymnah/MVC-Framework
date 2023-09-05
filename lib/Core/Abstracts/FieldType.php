<?php

namespace Core\Abstracts;

use Core\View;

abstract class FieldType
{
    const _E_INPUT = 'input';
    const _E_SELECT = 'select';
    const _E_OPTION = 'option';
    const _E_TEXTAREA = 'textarea';

    private $options;

    private $error;

    public function getDisplay()
    {
        $static = new static();
        $type = $static->type;

        $attr = [];
        foreach ($this->options['attr'] as $key => $value) {
            $attr[$key] = $value;
        }

        $value = $this->options['attr']['value'];
        $newAttr = [];
        foreach ($attr as $key => $eachAttr) {
            if (is_array($eachAttr)) {
                foreach ($eachAttr as $keyy => $eAttr) {
                    $newAttr[$keyy] = $keyy .'="' . $eAttr . '" ';
                }
            } else {
                $newAttr[$key] = $key .'="' . $eachAttr . '" ';
            }
        }

        $settings = [
            'attr' => implode('', $newAttr),
            'value' => $value,
            'error' => $this->error
        ];

        if ($type == self::_E_SELECT) {
            $optionsStr = '';
            $choices = array_merge($static->defaults['choices'], $this->options['choices']);

            foreach ($choices as $key => $choice) {
                $selected = '';
                if ($value == $choice) {
                    $selected = 'selected';
                }
                $choiceStr = 'value="' . $choice . '" ' . $selected;
                $optionsStr .= View::getInstance()->getContent('Globals/fieldTypes/' . self::_E_OPTION . '-element.php',[
                    'attr' => $choiceStr,
                    'label' => $key,
                ]);
            }

            $settings['options'] = $optionsStr;
        }

        $content = View::getInstance()->getContent('Globals/fieldTypes/' . $type . '-element.php', $settings);

        return htmlspecialchars($content);
    }

    public function getOptions($options)
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function setAttr($attr)
    {
        $this->options['attr'] = $attr;
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}