<?php

namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

/**
 * Entité représentant un covoiturage.
 * Contient les méthodes pour générer les URLs et afficher les informations du trajet.
 */
class CovoiturageEntity extends Entity
{

    /**
     * Constructeur de l'entité covoiturage.
     * 
     * @return void
     */
    public function __construct() {}

    /**
     * Génère l'URL de détail du covoiturage.
     * 
     * @return string URL vers la page de détail du trajet
     */
    public function getUrl()
    {
        return 'index.php?p=trajet-detail&id=' . $this->covoiturage_id;
    }

    /**
     * Génère le lien HTML pour voir les détails du covoiturage.
     * 
     * @return string Code HTML du lien vers les détails
     */
    public function getExtrait()
    {
        $html = '<a href="' . $this->getUrl() . '">Lire les détails </a>';
        return $html;
    }

    /**
     * Récupère le titre du covoiturage de manière sécurisée.
     * 
     * @return string Titre échappé pour l'affichage HTML
     */
    public function getTitle()
    {
        return htmlspecialchars($this->title);
    }

    /**
     * Convertit l'entité en chaîne de caractères.
     * Permet l'affichage dans les listes déroulantes.
     * 
     * @return string Titre du covoiturage
     */
    public function __toString()
    {
        // pour afficher le titre de l'article dans le select de la méthode lastByCategory.
        return $this->getTitle();
    }
}
