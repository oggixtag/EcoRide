<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des postes.
 * Fournit les méthodes de récupération des postes employés.
 */
class PosteModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'poste';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'id_poste';

    /**
     * Récupère les postes disponibles pour les employés (hors Administrateur).
     * 
     * @return array Liste des postes sans le poste Administrateur
     */
    public function getPosteEmploye()
    {
        return $this->query("SELECT * FROM poste WHERE intitule != 'Administrateur'");
    }
}
