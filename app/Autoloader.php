<?php

namespace NsAppEcoride;

/**
 * Classe Autoloader pour le namespace NsAppEcoride.
 * Permet le chargement automatique des classes de l'application
 * en utilisant la fonction spl_autoload_register de PHP.
 */
class Autoloader
{

    /**
     * Enregistre la fonction d'autoload auprès de PHP.
     * Utilise spl_autoload_register pour associer la méthode fautoload
     * au mécanisme d'autoloading de PHP.
     * 
     * @return void
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

    /**
     * Fonction d'autoload appelée par PHP lors du chargement d'une classe.
     * Charge automatiquement le fichier de la classe si elle appartient au namespace NsAppEcoride.
     * 
     * @param string $class Nom complet de la classe à charger (avec namespace)
     * @return void
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
