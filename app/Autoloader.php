<?php

namespace NsAppEcoride;

/**
 * Class Autoloader
 * Permet de charger automatiquement les classes
 */
class Autoloader
{

    /** Enregistre l'autoload 
     * 
     */
    static function register()
    {
        /**
         * On enregistre la méthode autoload avec la fonction spl_autoload_register
         *    __CLASS__ : nom de la classe courante (ici Autoloader)
         *    'autoload' : méthode à appeler (ici fautoload)
         */
        spl_autoload_register(array(__CLASS__, 'fautoload'));
    }

    /** Méthode d'autoload 
     * 
     * @param string $class_name : nom de la classe à charger
     */
    static function fautoload($class)
    {
        // La classe est dans le bon namespace
        if (strpos($class, __NAMESPACE__) === 0) {

            // On remplace les \ par des / pour les namespaces
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);

            $class = str_replace('\\', '/', $class);

            // On inclut le fichier de la classe
            // __DIR__ : répertoire parent du fichier courant, du coup app/
            require __DIR__ . '/' . $class . '.php';
        }
    }
}
