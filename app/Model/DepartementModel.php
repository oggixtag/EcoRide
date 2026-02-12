<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des départements.
 * Fournit les méthodes de base héritées de Model pour la table departement.
 */
class DepartementModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'departement';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'id_dept';
}
