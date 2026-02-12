<?php

namespace NsAppEcoride\Controller;

use NsCoreEcoride\Controller\Controller;
use App;

/**
 * Classe de base pour tous les contrôleurs de l'application.
 * Fournit les fonctionnalités communes comme le chargement des modèles et les redirections.
 */
class AppController extends Controller
{
    protected $template = 'default';

    /**
     * Constructeur du contrôleur de base.
     * Initialise le chemin des vues.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->viewPath = ROOT . '/app/Views/';
    }

    /**
     * Charge un modèle par son nom et l'attache au contrôleur.
     * 
     * @param string $modelName Nom du modèle à charger (ex: 'Utilisateur')
     * @return object Instance du modèle chargé
     */
    protected function loadModel($modelName)
    {
        return $this->$modelName = App::getInstance()->getTable($modelName);
    }

    /**
     * Récupère l'ID du dernier enregistrement inséré en base de données.
     * 
     * @return int ID du dernier enregistrement inséré
     */
    protected function getLastInsertId()
    {
        return App::getInstance()->getDb()->getlastInsertId();
    }

    /**
     * Redirige vers une URL spécifiée.
     * 
     * @param string $url URL de destination
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }
}
