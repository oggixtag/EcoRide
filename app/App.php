<?php

use NsCoreEcoride\Config;
use NsCoreEcoride\Database\MysqlDatabase;

class App
{

    // pour instancier la classe App
    private static $_instance;

    // pour instancier la classe Config
    private $db_instance;

    // Methode pour rendre la classe App en singleton.
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }

        return self::$_instance;
    }

    // 
    public static function load()
    {
        session_start();

        require ROOT . '/app/Autoloader.php';
        NsAppEcoride\Autoloader::register();

        require ROOT . '/core/Autoloader.php';
        NsCoreEcoride\Autoloader::register();

        require ROOT . '/vendor/autoload.php';
    }

    /**
     * Factoring pour les tables, creation à partir du nom d'une classe
     * 
     * public static function getTable($name) <=> avec l'injection de dépandences.
     * 
     * passage à l'injection de dépandences <=> suppression static.
     * Sinon : Fatal error: Uncaught Error: Using $this when not in object context in C:\xampp\htdocs\PHP POO\blog\app\App.php:38 Stack trace: #0 C:\xampp\htdocs\PHP POO\blog\public\index.php(102): NsAppBlog\App::getTable('Post') #1 {main} thrown in C:\xampp\htdocs\PHP POO\blog\app\App.php on line 38
     * 
     */
    public function getTable($name)
    {
        $class_name = '\\NsAppEcoride\\Model\\' . ucfirst($name) . 'Model';

        //nouvelle installation de classe pour $class_name.
        //Post<=>articles.
        //
        //injection de dépandences
        return new $class_name($this->getDb());
    }

    // Factory pour la connexion à base de données
    public function getDb()
    {
        $config = Config::getInstance(ROOT . '/config/config.php');

        //nouvelle installation de la classe MysqlDatabase.
        if (is_null($this->db_instance)) {
            $this->db_instance = new MysqlDatabase($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
        }

        return $this->db_instance;
    }
}
