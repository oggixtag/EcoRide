<?php

namespace NsCoreEcoride\HTML;

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
        return "<{$this->surround}>{$html}<{$this->surround}>";
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
        // si on passe le type en paramètre ça sera type sinon text par default.
        $type = isset($options['type']) ? $options['type'] : 'text';
        return $this->surround(
            '<input type="' . $type . '" name=""' . $name . '" value="' . $this->getValue($name) . '">'
        );
    }

    /**
     * @return string
     */
    public function submit()
    {
        return $this->surround('<button type="submit">Envoyer</button>');
    }
}
