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
        echo '<pre>';
        var_dump('App.getInstance().called.');
        echo '</pre>';

        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }

        return self::$_instance;
    }

    // 
    public static function load()
    {

        echo '<pre>';
        var_dump('App.load().called.');
        echo '</pre>';

        /*echo '<pre>';
        var_dump('App.load().session_start().');
        echo '</pre>';

        session_start();*/


        echo '<pre>';
        var_dump('App.load().require:' . ROOT . '/app/Autoloader.php');
        echo '</pre>';
        require ROOT . '/app/Autoloader.php';

        echo '<pre>';
        var_dump('App.load().calling NsAppEcoride\Autoloader::register()');
        echo '</pre>';
        NsAppEcoride\Autoloader::register();


        echo '<pre>';
        var_dump('App.load().require' . ROOT . '/core/Autoloader.php');
        echo '</pre>';
        require ROOT . '/core/Autoloader.php';

        echo '<pre>';
        var_dump('App.load().calling NsCoreEcoride\Autoloader::register()');
        echo '</pre>';
        NsCoreEcoride\Autoloader::register();
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
        echo '<pre>';
        var_dump('App.getTable().called for:' . $name . '.');
        echo '</pre>';

        $class_name = '\\NsAppEcoride\\Model\\' . ucfirst($name) . 'Model';

        echo '<pre>';
        var_dump('App.getTable().calling $this->getDb() pour instancier la classe:' . $class_name . '.');
        echo '</pre>';

        //nouvelle installation de classe pour $class_name.
        //Post<=>articles.
        //
        //injection de dépandences
        return new $class_name($this->getDb());
    }

    // Factory pour la connexion à base de données
    public function getDb()
    {

        echo '<pre>';
        var_dump('App.getDb().');
        echo '</pre>';

        echo '<pre>';
        var_dump('App.getDb().calling Config::getInstance()..');
        echo '</pre>';

        $config = Config::getInstance(ROOT . '/config/config.php');

        echo '<pre>';
        var_dump('App.getDb().if (is_null($this->db_instance))');
        echo '</pre>';

        //nouvelle installation de la classe MysqlDatabase.
        if (is_null($this->db_instance)) {

            echo '<pre>';
            var_dump('App.getDb().$this->db_instance = new MysqlDatabase().');
            echo '</pre>';

            $this->db_instance = new MysqlDatabase($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
        }

        echo '<pre>';
        var_dump('App.getDb().return $this->db_instance.');
        echo '</pre>';

        return $this->db_instance;
    }
}
