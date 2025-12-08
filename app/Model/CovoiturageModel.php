<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class CovoiturageModel extends Model
{
    protected $table = 'covoiturage';

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
        var_dump('CovoiturageModel.find().$id:' . $id . '.');
        /*var_dump('Table.find().$id..');
        foreach ($id as $key => $value) {
            var_dump(' ..' . '[' . $key . '] => ' . $value . '.');
        }*/
        echo '</pre>';

        return $this->query("
            select covoiturage_id,date_depart,heure_depart,lieu_depart,statut
            from covoiturage
            where lieu_depart = ?", [$id], true);
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
