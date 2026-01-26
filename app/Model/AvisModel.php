<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class AvisModel extends Model
{
    protected $table = 'avis';
    protected $column = 'avis_id';

    /**
     * Finds all reviews pending validation (statut_avis_id = 2 'modération')
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
     * Updates the status of a review
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
     * Finds a specific review with user details
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
