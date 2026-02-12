<?php

use NsCoreEcoride\Config;
use NsCoreEcoride\Database\MysqlDatabase;

/**
 * Classe principale de l'application EcoRide.
 * Implémente le pattern Singleton pour fournir un point d'accès unique
 * aux services de l'application (base de données, modèles).
 */
class App
{

    /** @var App|null Instance unique de la classe App (Singleton) */
    private static $_instance;

    /** @var MysqlDatabase|null Instance de connexion à la base de données */
    private $db_instance;

    /**
     * Récupère l'instance unique de l'application (Singleton).
     * Crée l'instance si elle n'existe pas encore.
     * 
     * @return App Instance unique de l'application
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }

        return self::$_instance;
    }

    /**
     * Charge et initialise l'application.
     * Démarre la session, enregistre les autoloaders et charge les dépendances.
     * 
     * @return void
     */
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
     * Factory pour les modèles (tables).
     * Crée et retourne une instance du modèle demandé avec injection de la base de données.
     * 
     * @param string $name Nom du modèle (ex: 'Utilisateur', 'Covoiturage')
     * @return object Instance du modèle demandé (ex: UtilisateurModel, CovoiturageModel)
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

    /**
     * Factory pour la connexion à la base de données (Singleton).
     * Crée et retourne l'instance de connexion MySQL.
     * Réutilise l'instance existante si déjà créée.
     * 
     * @return MysqlDatabase Instance de connexion à la base de données
     */
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
