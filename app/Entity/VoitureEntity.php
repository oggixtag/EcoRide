<?php
namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

/**
 * Entité représentant une voiture d'un utilisateur.
 * Contient les informations du véhicule utilisé pour les covoiturages.
 */
class VoitureEntity extends Entity
{
    /** @var int Identifiant unique de la voiture */
    public $voiture_id;
    
    /** @var string Modèle du véhicule */
    public $modele;
    
    /** @var string Numéro d'immatriculation */
    public $immatriculation;
    
    /** @var string Type d'énergie (Essence, Diesel, Électrique, Hybride) */
    public $energie;
    
    /** @var string Couleur du véhicule */
    public $couleur;
    
    /** @var string Date de première immatriculation au format YYYY-MM-DD */
    public $date_premiere_immatriculation;
    
    /** @var int Identifiant de la marque du véhicule */
    public $marque_id;
    
    /** @var int Identifiant du propriétaire (utilisateur) */
    public $utilisateur_id;

    // Optionnel : Méthodes pour formater les données ou getters/setters si une encapsulation stricte est nécessaire
    // Pour l'instant, les propriétés publiques suffisent comme pour les autres Entités du projet.
}
