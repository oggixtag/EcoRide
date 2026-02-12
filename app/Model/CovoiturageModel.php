<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des covoiturages.
 * Fournit les méthodes de recherche, participation, gestion de statut et annulation.
 */
class CovoiturageModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'covoiturage';
    
    /** @var string Nom de la colonne clé primaire */
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

    /**
     * Recherche des covoiturages par lieu de départ OU date de départ.
     * Utilisé pour afficher des alternatives lorsque la recherche exacte ne donne pas de résultats.
     * 
     * @param string $lieu_depart Ville de départ
     * @param string $date_depart Date de départ au format YYYY-MM-DD
     * @return array Liste des covoiturages correspondant à l'un des critères
     */
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

    /**
     * Récupère tous les covoiturages avec leurs détails.
     * Triés par date de départ décroissante puis lieu de départ.
     * 
     * @return array Liste de tous les covoiturages
     */
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

    /**
     * Récupère la liste de tous les statuts de covoiturage disponibles.
     * 
     * @return array Liste des statuts (prévu, en_cours, terminé, annulé, etc.)
     */
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
    /**
     * Annule un covoiturage, rembourse le conducteur et retourne les infos pour notification
     * @param int $covoiturage_id
     * @param int $driver_id
     * @return array|bool
     */
    public function cancelTrip($covoiturage_id, $driver_id)
    {
        // 1. Mettre à jour le statut à 'annulé' (1)
        $this->query(
            "UPDATE covoiturage SET statut_covoiturage_id = 1 WHERE covoiturage_id = ?",
            [$covoiturage_id]
        );

        // 2. Rembourser les crédits (2) au conducteur
        $this->query(
            "UPDATE utilisateur SET credit = credit + 2 WHERE utilisateur_id = ?",
            [$driver_id]
        );

        // 3. Préparer les données de notification
        $trip = $this->find($covoiturage_id);
        $participants = $this->getParticipants($covoiturage_id);

        return [
            'trip' => $trip,
            'participants' => $participants
        ];
    }

    /**
     * Trouve les covoiturages signalés comme "mal passé" (avis_covoiturage_id = 2)
     * @return array
     */
    public function findBadCarpools()
    {
        return $this->query(
            "SELECT 
                c.covoiturage_id,
                c.date_depart,
                c.lieu_depart,
                c.date_arrivee,
                c.lieu_arrivee,
                u_driver.pseudo as driver_pseudo,
                u_driver.email as driver_email,
                ac.libelle as motif
             FROM covoiturage c
             JOIN voiture v ON c.voiture_id = v.voiture_id
             JOIN utilisateur u_driver ON v.utilisateur_id = u_driver.utilisateur_id
             JOIN avis_covoiturage ac ON c.avis_covoiturage_id = ac.avis_covoiturage_id
             WHERE c.avis_covoiturage_id = 2"
        );
    }
    /**
     * Applique les filtres sur une liste de covoiturages.
     * 
     * @param array $covoiturages Liste des covoiturages à filtrer
     * @param array $filters Tableau des filtres (energie, prix_min, prix_max, duree_max, score_min)
     * @return array Liste des covoiturages filtrés
     */
    public function filterResults($covoiturages, $filters)
    {
        if (empty($covoiturages)) {
            return $covoiturages;
        }

        $filtered = $covoiturages;

        // Filtre par type d'énergie (écologique/standard)
        if (!empty($filters['energie']) && is_array($filters['energie'])) {
            $filtered = array_filter($filtered, function ($covoiturage) use ($filters) {
                $energie_normalized = strtolower($this->removeAccents($covoiturage->energie));
                $est_ecologique = ($energie_normalized === 'electrique');

                // Vérifier si 'écologique' et/ou 'standard' sont sélectionnés
                $inclure_ecologique = in_array('ecologique', $filters['energie']);
                $inclure_standard = in_array('standard', $filters['energie']);

                // Retourner true si le covoiturage correspond à l'une des sélections
                if ($inclure_ecologique && $est_ecologique) {
                    return true;
                }
                if ($inclure_standard && !$est_ecologique) {
                    return true;
                }

                return false;
            });
        }

        // Filtre par prix minimum
        if (!empty($filters['prix_min'])) {
            $prix_min = floatval($filters['prix_min']);
            $filtered = array_filter($filtered, function ($covoiturage) use ($prix_min) {
                return floatval($covoiturage->prix_personne) >= $prix_min;
            });
        }

        // Filtre par prix maximum
        if (!empty($filters['prix_max'])) {
            $prix_max = floatval($filters['prix_max']);
            $filtered = array_filter($filtered, function ($covoiturage) use ($prix_max) {
                return floatval($covoiturage->prix_personne) <= $prix_max;
            });
        }

        // Filtre par durée maximale du voyage
        if (!empty($filters['duree_max'])) {
            $duree_max_heures = floatval($filters['duree_max']); // en heures (1-12 du slider)
            $duree_max_minutes = $duree_max_heures * 60; // convertir en minutes
            $filtered = array_filter($filtered, function ($covoiturage) use ($duree_max_minutes) {
                $depart = \DateTime::createFromFormat('Y-m-d H:i:s', $covoiturage->date_depart . ' ' . $covoiturage->heure_depart);
                $arrivee = \DateTime::createFromFormat('Y-m-d H:i:s', $covoiturage->date_arrivee . ' ' . $covoiturage->heure_arrivee);
                if ($depart && $arrivee) {
                    $interval = $arrivee->diff($depart);
                    $duree_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                    return $duree_minutes <= $duree_max_minutes;
                }
                return true;
            });
        }

        // Filtre par score minimum (Non implémenté en DB pour l'instant)
        if (!empty($filters['score_min'])) {
            // $score_min = floatval($filters['score_min']);
            // $filtered = array_filter($filtered, function ($covoiturage) use ($score_min) {
            //     return floatval($covoiturage->score) >= $score_min;
            // });
        }

        return array_values($filtered); // Réinitialiser les clés du tableau
    }

    /**
     * Supprime les accents d'une chaîne de caractères.
     * 
     * @param string $str Chaîne de caractères avec accents
     * @return string Chaîne de caractères sans accents
     */
    private function removeAccents($str)
    {
        $str = (string) $str;
        $map = array(
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y', 'À' => 'A', 'Á' => 'A',
            'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y'
        );
        return strtr($str, $map);
    }
}
