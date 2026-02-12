<?php

namespace NsAppEcoride\Controller;

/**
 * Contrôleur pour les pages légales.
 * Gère l'affichage des mentions légales, CGU, politique de confidentialité, etc.
 */
class LegalesController extends AppController
{
    /**
     * Constructeur du contrôleur des pages légales.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Affiche la page d'index des mentions légales.
     * Liste les différentes sections légales disponibles.
     * 
     * @return void Affiche la vue legales.index
     */
    public function index()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'legales.index',
            []
        );
    }
}
