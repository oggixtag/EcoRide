<?php
namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

/**
 * Entité représentant un avis utilisateur.
 * Contient les informations sur les commentaires et notes laissés par les utilisateurs.
 */
class AvisEntity extends Entity
{
    /** @var int Identifiant unique de l'avis */
    public $avis_id;
    
    /** @var string Contenu du commentaire */
    public $commentaire;
    
    /** @var int Identifiant de la note attribuée */
    public $note_id;
    
    /** @var int Identifiant du statut de l'avis (publié, en modération, etc.) */
    public $statut_avis_id;
    
    /** @var int Identifiant de l'utilisateur auteur de l'avis */
    public $utilisateur_id;

    // Propriétés provenant des jointures (souvent hydratées dynamiquement)
    
    /** @var string Pseudo de l'utilisateur (jointure) */
    public $pseudo;
    
    /** @var int|string Note attribuée (jointure) */
    public $note;
    
    /** @var string Statut de l'avis (jointure) */
    public $statut;
}
