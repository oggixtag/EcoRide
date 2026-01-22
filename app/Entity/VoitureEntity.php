<?php
namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

class VoitureEntity extends Entity
{
    public $voiture_id;
    public $modele;
    public $immatriculation;
    public $energie;
    public $couleur;
    public $date_premiere_immatriculation;
    public $marque_id;
    public $utilisateur_id;

    // Optional: Methods to format data or getters/setters if strict encapsulation is needed
    // For now, public properties suffice as per other Entities in the project.
}
