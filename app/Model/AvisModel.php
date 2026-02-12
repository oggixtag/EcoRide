<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des avis utilisateurs.
 * Fournit les méthodes CRUD et de recherche pour les avis.
 */
class AvisModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'avis';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'avis_id';

    /**
     * Trouve tous les avis en attente de validation (statut_avis_id = 2 'modération')
     * @return array
     */
    public function findAllPending()
    {
        return $this->query(
            "SELECT a.*, u.pseudo, n.libelle as note, sa.libelle as statut
             FROM avis a
             JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
             JOIN note n ON a.note_id = n.note_id
             JOIN statut_avis sa ON a.statut_avis_id = sa.statut_avis_id
             WHERE sa.libelle = 'modération'"
        );
    }

    /**
     * Met à jour le statut d'un avis
     * @param int $id
     * @param int $statusId
     * @return bool
     */
    public function updateStatus($id, $statusId)
    {
        return $this->query(
            "UPDATE avis SET statut_avis_id = ? WHERE avis_id = ?",
            [$statusId, $id]
        );
    }

    /**
     * Trouve un avis spécifique avec les détails de l'utilisateur
     * @param int $id
     * @return object|null
     */
    public function findOneWithUser($id)
    {
         return $this->query(
            "SELECT a.*, u.pseudo, u.email, n.libelle as note, sa.libelle as statut
             FROM avis a
             JOIN utilisateur u ON a.utilisateur_id = u.utilisateur_id
             JOIN note n ON a.note_id = n.note_id
             JOIN statut_avis sa ON a.statut_avis_id = sa.statut_avis_id
             WHERE a.avis_id = ?",
             [$id],
             true
         );
    }
}
