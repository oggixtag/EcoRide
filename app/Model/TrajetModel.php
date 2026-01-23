<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class TrajetModel extends Model
{
    protected $table = 'covoiturage';
    protected $column = 'covoiturage_id';

    /**
     * Crée un nouveau trajet
     * @param array $data Les données du trajet (date_depart, heure_depart, lieu_depart, etc.)
     * @return bool|int L'ID du trajet créé ou false en cas d'erreur
     */
    public function create($data)
    {
        return $this->query("INSERT INTO {$this->table} (
            date_depart, 
            heure_depart, 
            lieu_depart, 
            date_arrivee, 
            heure_arrivee, 
            lieu_arrivee, 
            statut_covoiturage_id, 
            nb_place, 
            prix_personne, 
            voiture_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            $data['date_depart'],
            $data['heure_depart'],
            $data['lieu_depart'],
            $data['date_arrivee'],
            $data['heure_arrivee'],
            $data['lieu_arrivee'],
            $data['statut_covoiturage_id'],
            $data['nb_place'],
            $data['prix_personne'],
            $data['voiture_id']
        ]);
    }

    /**
     * Met à jour un trajet existant
     * @param int $id L'ID du trajet
     * @param array $data Les nouvelles données
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->query("UPDATE {$this->table} SET 
            date_depart = ?, 
            heure_depart = ?, 
            lieu_depart = ?, 
            date_arrivee = ?, 
            heure_arrivee = ?, 
            lieu_arrivee = ?, 
            nb_place = ?, 
            prix_personne = ?, 
            voiture_id = ?
            WHERE covoiturage_id = ?", [
            $data['date_depart'],
            $data['heure_depart'],
            $data['lieu_depart'],
            $data['date_arrivee'],
            $data['heure_arrivee'],
            $data['lieu_arrivee'],
            $data['nb_place'],
            $data['prix_personne'],
            $data['voiture_id'],
            $id
        ]);
    }

    /**
     * Supprime un trajet
     * @param int $id L'ID du trajet
     * @return bool
     */
    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE covoiturage_id = ?", [$id]);
    }
}
