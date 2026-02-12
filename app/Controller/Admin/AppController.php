<?php

namespace NsAppEcoride\Controller\Admin;

use \App;
use NsCoreEcoride\Auth\DbAuth;

/**
 * Classe de base pour les contrôleurs d'administration.
 * Utilise le template admin et fournit les vérifications d'authentification.
 */
class AppController extends \NsAppEcoride\Controller\AppController
{
    protected $template = 'admin';

    /**
     * Constructeur du contrôleur admin de base.
     * Initialise l'application et l'authentification.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $app = App::getInstance();

        $auth = new DbAuth($app->getDb());

        // Vérifier si connecté en tant qu'employé (puisque c'est la zone Admin/Employé)
        // Les vérifications sont maintenant gérées dans les contrôleurs individuels pour plus de granularité
        // if (!$auth->isEmploye() && isset($_GET['p']) && $_GET['p'] !== 'admin.employes.login') {
        //    $this->forbidden();
        // }
    }
}
