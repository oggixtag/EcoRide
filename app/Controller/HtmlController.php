<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

/**
 * Contrôleur pour les pages HTML statiques.
 * Gère l'affichage des pages informatives comme la philosophie et le contact.
 */
class HtmlController extends AppController
{
    /**
     * Affiche la page de philosophie d'EcoRide.
     * Présente les valeurs et la vision écologique de la plateforme.
     * 
     * @return void Affiche la vue covoiturages.philosophie
     */
    public function philosophie()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.philosophie',
            []
        );
    }

    /**
     * Affiche la page de contact d'EcoRide.
     * Permet aux utilisateurs de contacter le support.
     * 
     * @return void Affiche la vue covoiturages.contact
     */
    public function contact()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.contact',
            []
        );
    }
}
