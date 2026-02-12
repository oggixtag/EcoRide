<?php

namespace NsCoreEcoride\HTML;

class MyForm extends Form
{

    /**
     * @param string $html
     * @return string
     */
    protected function surround($html)
    {
        return "<div class=\"form-group\">$html</div>";
    }

    /**
     * @param $name string
     * @param $label string
     * @param $option array
     * @return string
     */
    public function input($name, $label, $options = [])
    {
        $type = isset($options['type']) ? $options['type'] : 'text';
        $label = '<label>' . $label . '</label>';
        if ($type === 'textarea') {
            $input = '<textarea name="' . $name . '" class="form-control">' . $this->getValue($name) . '</textarea>';
        } else {
            $input = '<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '" class="form-control">';
        }

        return $this->surround($label . $input);
    }

    public function select($name, $label, $options, $extraOptions = [])
    {
        $label = '<label>' . $label . '</label>';
        $input = '<select class="form-control" name="' . $name . '">';
        foreach ($options as $key => $value) {
            $attribute = '';
            if ($key == $this->getValue($name)) {
                $attribute = 'selected';
            }
            $input .= "<option value='$key' $attribute >$value</option>";
        }
        $input .= '</select>';
        return $this->surround($label . $input);
    }

    /**
     * @return string
     */
    public function submit()
    {
        return $this->surround('<button type="submit" class="btn btn-primary">Envoyer</button>');
    }
}
