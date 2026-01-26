<?php
namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

class AvisEntity extends Entity
{
    public $avis_id;
    public $commentaire;
    public $note_id;
    public $statut_avis_id;
    public $utilisateur_id;

    // Properties from joins (often hydrated dynamically)
    public $pseudo;
    public $note;
    public $statut;
}
