<?php

namespace NsCoreEcoride\Html;

class Form
{

    /**
     * @var array Donnée utilisée par le formulaire
     */
    private $data;

    /**
     * @var string Balise HTML utilisée pour entourer les champs du formulaire
     */
    public $surround = 'p';

    /**
     * @param array Donnée utilisée par le formulaire
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }


    /**
     * @param $html string Code HTML à entourer
     * @return string
     */
    protected function surround($html)
    {
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    /**
     * @param $index string Index de la valeur à récupérer
     * @return string
     */
    protected function getValue($index)
    {
        // Cannot use object of type App\Entity\PostEntity as array
        // C'est necessaire car depuis edit.php je recupère les données depuis Entity (qui rappresent l'enregistrement).
        // du coup $this->data ce n'est pas un tableau mais une entité 
        if (is_object($this->data)) {
            return $this->data->$index;
        }
        return isset($this->data[$index]) ? $this->data[$index] : null;
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
        $required = isset($options['required']) && $options['required'] ? ' required' : '';
        $value = $this->getValue($name);
        
        if ($required) {
            $label .= ' <span style="color: red;">*</span>';
        }
        
        if ($type === 'textarea') {
            $input = '<textarea name="' . $name . '" class="form-control" style="flex:1;"' . $required . '>' . $value . '</textarea>';
        } else {
            $step = isset($options['step']) ? ' step="' . $options['step'] . '"' : '';
            if ($type === 'number' && isset($options['step']) && $options['step'] == '1' && is_numeric($value)) {
                $value = (int)$value;
            }
            $input = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" class="form-control" style="flex:1;"' . $required . $step . '>';
        }
        
        return '
            <div class="form-group" style="display: flex; align-items: center; margin-bottom: 7px;">
                <label style="width: 150px; font-weight: bold; margin-right: 15px;">' . $label . '</label>
                ' . $input . '
            </div>';
    }

    /**
     * @return string
     */
    public function submit()
    {
        return '<div style="margin-top: 20px;"><button type="submit" class="btn btn-primary">Envoyer</button></div>';
    }

    public function select($name, $label, $options, $extraOptions = [])
    {
        $required = isset($extraOptions['required']) && $extraOptions['required'] ? ' required' : '';
        
        if ($required) {
            $label .= ' <span style="color: red;">*</span>';
        }

        $input = '<select class="form-control" name="' . $name . '" style="flex:1;"' . $required . '>';
        foreach ($options as $k => $v) {
            $attributes = '';
            if ($k == $this->getValue($name)) {
                $attributes = ' selected';
            }
            $input .= "<option value='$k'$attributes>$v</option>";
        }
        $input .= '</select>';
        
        return '
            <div class="form-group" style="display: flex; align-items: center; margin-bottom: 7px;">
                <label style="width: 150px; font-weight: bold; margin-right: 15px;">' . $label . '</label>
                ' . $input . '
            </div>';
    }
}
