<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des voitures.
 * Fournit les méthodes CRUD pour les véhicules des utilisateurs.
 */
class VoitureModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'voiture';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'voiture_id';

    /**
     * Récupère toutes les voitures d'un utilisateur
     * @param int $utilisateur_id
     * @return array
     */
    public function getVoituresByUserId($utilisateur_id)
    {
        return $this->query(
            "SELECT 
                v.*, 
                m.libelle as marque
            FROM {$this->table} v
            JOIN marque m ON v.marque_id = m.marque_id
            WHERE v.utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Ajoute une nouvelle voiture
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        return $this->query(
            "INSERT INTO {$this->table} (modele, immatriculation, energie, couleur, date_premiere_immatriculation, marque_id, utilisateur_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['modele'],
                $data['immatriculation'],
                $data['energie'],
                $data['couleur'],
                $data['date_premiere_immatriculation'],
                $data['marque_id'],
                $data['utilisateur_id']
            ]
        );
    }

    /**
     * Met à jour une voiture
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->query(
            "UPDATE {$this->table} SET 
                modele = ?, 
                immatriculation = ?, 
                energie = ?, 
                couleur = ?, 
                date_premiere_immatriculation = ?, 
                marque_id = ?
            WHERE voiture_id = ? AND utilisateur_id = ?",
            [
                $data['modele'],
                $data['immatriculation'],
                $data['energie'],
                $data['couleur'],
                $data['date_premiere_immatriculation'],
                $data['marque_id'],
                $id,
                $data['utilisateur_id']
            ]
        );
    }

    /**
     * Supprime une voiture
     * @param int $id
     * @param int $utilisateur_id (Sécurité pour s'assurer que l'utilisateur est bien le propriétaire)
     * @return bool
     */
    public function deleteCar($id, $utilisateur_id)
    {
        return $this->query(
            "DELETE FROM {$this->table} WHERE voiture_id = ? AND utilisateur_id = ?",
            [$id, $utilisateur_id]
        );
    }

    /**
     * Récupère la liste des marques pour le formulaire
     * @return array
     */
    public function getMarques()
    {
        return $this->query("SELECT * FROM marque ORDER BY libelle");
    }
    /**
     * Vérifie si une voiture a des covoiturages associés
     * @param int $voiture_id
     * @return bool
     */
    public function hasCovoiturages($voiture_id)
    {
        $result = $this->query(
            "SELECT COUNT(*) as count FROM covoiturage WHERE voiture_id = ?",
            [$voiture_id],
            true // un seul résultat
        );
        return $result->count > 0;
    }
}
