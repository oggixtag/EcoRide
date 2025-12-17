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
        var_dump('CovoiturageModel.recherche().$lieu_depart:' . $date_depart . '.');
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
        var_dump('CovoiturageModel.recherche_lieu_ou_date().$lieu_depart:' . $date_depart . '.');
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
     * Récupèr les derniers artcicles de la category selectionnée
     * @param $category_id int
     * @return array
     */
    public function lastByCategory($category_id)
    {
        echo '<pre>';
        var_dump('CovoiturageModel.lastByCategory() called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoiturageModel.lastByCategory().$category_id:' . $category_id . '.');
        /*var_dump('CovoiturageModel.lastByCategory().$category_id..');
        foreach ($category_id as $key => $value) {
            var_dump(' ..' . '[' . $key . '] => ' . $value . '.');
        }*/
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoiturageModel.lastByCategory().calling query()..');
        echo '</pre>';

        return $this->query("select a.title ,a.content
        from articles a 
        left join categories c on a.category_id = c.id 
        where a.category_id = ?
        order by a.id DESC", [$category_id]);
    }
}
