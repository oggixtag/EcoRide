<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class CovoiturageModel extends Model
{
    protected $table = 'covoiturage';

    /**
     * Rècupere un article en lian la categorie associée 
     * @param $lieu_depart string
     * @param $date_depart date
     * @return \App\Entity\CovoiturageEntity
     */
    public function recherche($lieu_depart, $date_depart)
    {
        echo '<pre>';
        var_dump('CovoiturageModel.recherche() called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoiturageModel.recherche().$lieu_depart:' . $lieu_depart . '.');
        var_dump('CovoiturageModel.recherche().$date_depart:' . $date_depart . '.');
        echo '</pre>';

        return $this->query(
            "select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                c.statut		 ,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join utilisateur u on (u.utilisateur_id = c.utilisateur_id)
            join voiture v on (v.voiture_id = c.voiture_id)
            where   c.lieu_depart = ?
                and c.date_depart = ?",
            [$lieu_depart, $date_depart]
        );
    }

    public function recherche_lieu_ou_date($lieu_depart, $date_depart)
    {
        echo '<pre>';
        var_dump('CovoiturageModel.rechrecherche_lieu_ou_dateerche() called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoiturageModel.recherche_lieu_ou_date().$lieu_depart:' . $lieu_depart . '.');
        var_dump('CovoiturageModel.recherche_lieu_ou_date().$date_depart:' . $date_depart . '.');
        echo '</pre>';

        return $this->query(
            "select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                c.statut		 ,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join utilisateur u on (u.utilisateur_id = c.utilisateur_id)
            join voiture v on (v.voiture_id = c.voiture_id)
            where   c.lieu_depart = ?
                or c.date_depart = ?",
            [$lieu_depart, $date_depart]
        );
    }

    /**
     * Rècupere un article en lian la categorie associée 
     * @param $id string
     * @return \App\Entity\CovoiturageEntity
     */
    public function find($id)
    {
        echo '<pre>';
        var_dump('CovoiturageModel.find() called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoiturageModel.find().$lieu_depart:' . $id . '.');
        /*var_dump('Table.find().$id..');
        foreach ($id as $key => $value) {
            var_dump(' ..' . '[' . $key . '] => ' . $value . '.');
        }*/
        echo '</pre>';

        return $this->query("
            select 
                c.covoiturage_id ,
                c.date_depart	 ,
                c.heure_depart	 ,
                c.lieu_depart	 ,
                c.date_arrivee	 ,
                c.heure_arrivee	 ,
                c.lieu_arrivee	 ,
                c.statut		 ,
                c.nb_place		 ,
                c.prix_personne	 ,
                u.pseudo		 ,
                v.energie
            from covoiturage c
            join utilisateur u on (u.utilisateur_id = c.utilisateur_id)
            join voiture v on (v.voiture_id = c.voiture_id)
            where  c.lieu_depart = ?", [$id], true);
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
                c.date_depart,
                c.heure_depart,
                c.lieu_depart,
                c.date_arrivee,
                c.heure_arrivee,
                c.lieu_arrivee,
                c.statut,
                c.nb_place,
                c.prix_personne,
                u.pseudo,
                u.utilisateur_id,
                v.energie,
                v.modele,
                v.couleur,
                m.libelle as marque
            from covoiturage c
            join utilisateur u on (u.utilisateur_id = c.utilisateur_id)
            join voiture v on (v.voiture_id = c.voiture_id)
            join marque m on (m.marque_id = v.marque_id)
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
                a.note,
                a.commentaire,
                a.statut
            from avis a
            join utilisateur u on (u.utilisateur_id = a.utilisateur_id)
            where a.utilisateur_id = ?
            order by a.avis_id desc
            limit 10",
            [$covoiturage->utilisateur_id]
        );

        // Ajouter les avis au covoiturage
        $covoiturage->avis = $avis ?? [];

        return $covoiturage;
    }
}
