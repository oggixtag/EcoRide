<?php

namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

/**
 * Entité représentant un utilisateur de la plateforme.
 * Contient les informations personnelles et les méthodes d'accès aux données utilisateur.
 */
class UtilisateurEntity extends Entity
{
    /** @var int Identifiant unique de l'utilisateur */
    public $utilisateur_id;
    
    /** @var string Pseudo de l'utilisateur */
    public $pseudo;
    
    /** @var string Mot de passe hashé */
    public $password;
    
    /** @var string Nom de famille */
    public $nom;
    
    /** @var string Prénom */
    public $prenom;
    
    /** @var string Adresse email */
    public $email;
    
    /** @var string Numéro de téléphone */
    public $telephone;
    
    /** @var string Adresse postale */
    public $adresse;
    
    /** @var string Date de naissance au format YYYY-MM-DD */
    public $date_naissance;
    
    /** @var string|null Chemin vers la photo de profil */
    public $photo;
    
    /** @var int Identifiant du rôle (1=admin, 2=passager, 3=chauffeur-passager) */
    public $role_id;
    
    /** @var int Nombre de crédits disponibles */
    public $credit;
    
    /** @var int Indicateur de suspension (0 = actif, 1 = suspendu) */
    public $est_suspendu;

    /**
     * Constructeur de l'entité utilisateur.
     * 
     * @return void
     */
    public function __construct() {}

    /**
     * Génère l'URL vers le profil de l'utilisateur.
     * 
     * @return string URL vers la page de profil
     */
    public function getUrl()
    {
        return 'index.php?p=utilisateur&id=' . $this->utilisateur_id;
    }

    /**
     * Récupère le nom complet de l'utilisateur.
     * 
     * @return string Prénom et nom échappés pour l'affichage HTML
     */
    public function getFullName()
    {
        return htmlspecialchars($this->prenom . ' ' . $this->nom);
    }

    /**
     * Génère le lien HTML pour voir le profil de l'utilisateur.
     * 
     * @return string Code HTML du lien vers le profil
     */
    public function getExtrait()
    {
        $html = '<a href="' . $this->getUrl() . '">Voir le profil</a>';
        return $html;
    }

    /**
     * Récupère le pseudo de l'utilisateur de manière sécurisée.
     * 
     * @return string Pseudo échappé pour l'affichage HTML
     */
    public function getPseudo()
    {
        return htmlspecialchars($this->pseudo);
    }


    /**
     * Retourne le rôle de l'utilisateur de manière sécurisée.
     * 
     * @return string Rôle échappé pour l'affichage HTML
     */
    public function getRole()
    {
        return htmlspecialchars($this->role);
    }

    /**
     * Retourne l'email de l'utilisateur de manière sécurisée.
     * 
     * @return string Email échappé pour l'affichage HTML
     */
    public function getEmail()
    {
        return htmlspecialchars($this->email);
    }
}
