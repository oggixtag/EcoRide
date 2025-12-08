<?php

namespace NsCoreEcoride;

class Config
{

    /**
     * Classe pour instancier en singleton la configuration des paramètres pour la connexion à la base des données.
     */
    private $settings;
    private $id;

    private static $_instance; //$_ pour differencier les variables private de variables static.

    private function __construct($file)
    {
        $this->id = uniqid();
        //dirname(__DIR__) . '/config/config.php'
        $this->settings = require($file);
    }

    // Methode pour rendre la classe Config en singleton.
    public static function getInstance($file)
    {

        echo '<pre>';
        var_dump('Config.getInstance().');
        echo '</pre>';

        if (is_null(self::$_instance)) {
            self::$_instance = new Config($file);
        }

        echo '<pre>';
        var_dump('Config.getInstance().return.');
        echo '</pre>';

        return self::$_instance;
    }

    public static function getInstanceId()
    {
        return self::$id;
    }

    // recuper la clé de la proprieté de la classe qui a été passée en paramètre
    public function get($key)
    {
        if (!isset($this->settings[$key])) {
            return null;
        }
        return $this->settings[$key];
    }
}
