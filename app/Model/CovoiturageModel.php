<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class CovoiturageModel extends Model
{
    protected $table = 'covoiturage';
    protected $column = 'covoiturage_id';

    /**
     * Rècupere un article en lian la categorie associée 
     * @param $lieu_depart string
     * @param $date_depart date
     * @return \App\Entity\CovoiturageEntity
     */
    public function recherche($lieu_depart, $date_depart)
    {
        return $this->query(
            "select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                s.libelle as statut,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join voiture v on (v.voiture_id = c.voiture_id)
            join utilisateur u on (u.utilisateur_id = v.utilisateur_id)
            join statut_covoiturage s on (c.statut_covoiturage_id = s.statut_covoiturage_id)
            where   c.lieu_depart = ?
                and c.date_depart = ?",
            [$lieu_depart, $date_depart]
        );
    }

    public function recherche_lieu_ou_date($lieu_depart, $date_depart)
    {
        return $this->query(
            "select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                s.libelle as statut,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join voiture v on (v.voiture_id = c.voiture_id)
            join utilisateur u on (u.utilisateur_id = v.utilisateur_id)
            join statut_covoiturage s on (c.statut_covoiturage_id = s.statut_covoiturage_id)
            where   c.lieu_depart = ?
                or c.date_depart = ?",
            [$lieu_depart, $date_depart]
        );
    }

    public function all()
    {
        return $this->query(
            "select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                s.libelle as statut,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join voiture v on (v.voiture_id = c.voiture_id)
            join utilisateur u on (u.utilisateur_id = v.utilisateur_id)
            join statut_covoiturage s on (c.statut_covoiturage_id = s.statut_covoiturage_id)
            order by c.date_depart desc, c.lieu_depart asc",
            false
        );
    }
    
    
    /**
     * Récupère un covoiturage par son ID
     * @param $id int ID du covoiturage
     * @return \App\Entity\CovoiturageEntity
     */
    public function find($id)
    {
        return $this->query("
            select 
                c.covoiturage_id ,
                c.statut_covoiturage_id,
                c.voiture_id,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                s.libelle as statut,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join voiture v on (v.voiture_id = c.voiture_id)
            join utilisateur u on (u.utilisateur_id = v.utilisateur_id)
            join statut_covoiturage s on (c.statut_covoiturage_id = s.statut_covoiturage_id)
            where  c.covoiturage_id = ?", [$id], true);
    }

    /**
     * Récupère les détails complets d'un covoiturage avec véhicule, conducteur et avis
     * @param $covoiturage_id int ID du covoiturage
     * @return object|null Objet covoiturage avec détails complets
     */
    public function findWithDetails($covoiturage_id)
    {
        // Récupérer les informations du covoiturage avec ses données liées
        $covoiturage = $this->query(
            "select 
                c.covoiturage_id,
                c.statut_covoiturage_id,
                c.date_depart,
                c.heure_depart,
                c.lieu_depart,
                c.date_arrivee,
                c.heure_arrivee,
                c.lieu_arrivee,
                s.libelle as statut,
                c.nb_place,
                c.prix_personne,
                c.voiture_id,
                u.pseudo,
                u.utilisateur_id,
                v.energie,
                v.modele,
                v.couleur,
                m.libelle as marque
            from covoiturage c
            join voiture v on (v.voiture_id = c.voiture_id)
            join utilisateur u on (u.utilisateur_id = v.utilisateur_id)
            join marque m on (m.marque_id = v.marque_id)
            join statut_covoiturage s on (c.statut_covoiturage_id = s.statut_covoiturage_id)
            where c.covoiturage_id = ?",
            [$covoiturage_id],
            true
        );

        if (!$covoiturage) {
            return null;
        }

        // Récupérer les avis du conducteur
        $avis = $this->query(
            "select 
                a.avis_id,
                n.libelle as note,
                a.commentaire,
                sa.libelle as statut
            from avis a
            join utilisateur u on (u.utilisateur_id = a.utilisateur_id)
            join statut_avis sa on (a.statut_avis_id = sa.statut_avis_id)
            join note n on (a.note_id = n.note_id)
            where a.utilisateur_id = ?
            order by a.avis_id desc
            limit 10",
            [$covoiturage->utilisateur_id]
        );

        // Ajouter les avis au covoiturage
        $covoiturage->avis = $avis ?? [];

        return $covoiturage;
    }

    /**
     * Enregistre la participation d'un utilisateur à un covoiturage
     * @param $utilisateur_id int ID de l'utilisateur
     * @param $covoiturage_id int ID du covoiturage
     * @return bool true si l'enregistrement a réussi, false sinon
     */
    public function enregistrerParticipation($utilisateur_id, $covoiturage_id)
    {
        // Vérifier que l'utilisateur n'est pas déjà inscrit
        $exists = $this->query(
            "SELECT COUNT(*) as count FROM participe 
            WHERE utilisateur_id = ? AND covoiturage_id = ?",
            [$utilisateur_id, $covoiturage_id],
            true
        );

        if ($exists && $exists->count > 0) {
            return false; // L'utilisateur est déjà inscrit
        }

        // Enregistrer la participation
        $result = $this->query(
            "INSERT INTO participe (utilisateur_id, covoiturage_id) 
            VALUES (?, ?)",
            [$utilisateur_id, $covoiturage_id]
        );

        return $result > 0;
    }

    /**
     * Vérifie si un utilisateur a déjà réservé un covoiturage
     * @param int $covoiturage_id
     * @param int $utilisateur_id
     * @return bool
     */
    public function hasUserReserved($covoiturage_id, $utilisateur_id)
    {
        $result = $this->query(
            "SELECT COUNT(*) as count FROM participe 
            WHERE covoiturage_id = ? AND utilisateur_id = ?",
            [$covoiturage_id, $utilisateur_id],
            true
        );
        return $result && $result->count > 0;
    }

    /**
     * Récupère les participations d'un utilisateur
     * @param int $utilisateur_id
     * @return array
     */
    public function getParticipationsForUser($utilisateur_id)
    {
        return $this->query(
            "SELECT covoiturage_id FROM participe 
            WHERE utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Déduit une place du covoiturage
     * @param int $covoiturage_id
     * @return bool
     */
    public function deduirePlace($covoiturage_id)
    {
        $result = $this->query(
            "UPDATE covoiturage 
            SET nb_place = nb_place - 1 
            WHERE covoiturage_id = ? AND nb_place > 0",
            [$covoiturage_id]
        );
        return $result > 0;
    }

    public function getListStatuts()
    {
        return $this->query("SELECT * FROM statut_covoiturage");
    }

    /**
     * Met à jour le statut d'un covoiturage
     * @param int $covoiturage_id
     * @param int $statut_id
     * @return bool
     */
    public function updateStatut($covoiturage_id, $statut_id)
    {
        $result = $this->query(
            "UPDATE covoiturage 
            SET statut_covoiturage_id = ? 
            WHERE covoiturage_id = ?",
            [$statut_id, $covoiturage_id]
        );
        return $result;
    }

    /**
     * Récupère les participants d'un covoiturage
     * @param int $covoiturage_id
     * @return array Liste des participants (email, pseudo)
     */
    public function getParticipants($covoiturage_id)
    {
        return $this->query(
            "SELECT u.email, u.pseudo 
            FROM utilisateur u 
            JOIN participe p ON u.utilisateur_id = p.utilisateur_id 
            WHERE p.covoiturage_id = ?",
            [$covoiturage_id]
        );
    }
}
