<?php

namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;


/**
 * Entité représentant un employé de l'entreprise.
 * Contient les informations personnelles et professionnelles des employés.
 */
class EmployeEntity extends Entity
{
    /** @var int Identifiant unique de l'employé */
    public $id_emp;
    
    /** @var string Nom de famille de l'employé */
    public $nom;
    
    /** @var string Prénom de l'employé */
    public $prenom;
    
    /** @var string Adresse email professionnelle */
    public $email;
    
    /** @var string Mot de passe hashé */
    public $password;
    
    /** @var int Indicateur de suspension (0 = actif, 1 = suspendu) */
    public $est_suspendu;
    
    /** @var int Identifiant du poste occupé */
    public $id_poste;
    
    /** @var int Identifiant du département */
    public $id_dept;
    
    /** @var int|null Identifiant du manager (null si pas de manager) */
    public $id_manager;
    
    /** @var string Date d'embauche au format YYYY-MM-DD */
    public $date_embauche;
    
    /** @var float Salaire de l'employé */
    public $salaire;
}
