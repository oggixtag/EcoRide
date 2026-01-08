<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class CovoituragesController extends AppController
{

    public function __construct()
    {
        echo '<pre>';
        var_dump('CovoituragesController.__construct()');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoituragesController.__construct().calling parent::__construct()');
        echo '</pre>';
        parent::__construct();

        echo '<pre>';
        var_dump('CovoituragesController.__construct().calling $this->loadModel for Covoiturage');
        echo '</pre>';
        $this->loadModel('Covoiturage');
    }

    public function index()
    {
        echo '<pre>';
        var_dump('CovoituragesController.index().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoituragesController.index().calling render()..');
        echo '</pre>';

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.index',
            compact('')
        );
    }

    public function show()
    {
        echo '<pre>';
        var_dump('CovoituragesController.find().called on methed ' . $_SERVER['REQUEST_METHOD'] . '.');
        echo '</pre>';

        /*Pour la recherche d’itinéraire, la recherche se basera sur la ville ainsi que la date. */
        $error_form_recherche = false;

        if (empty($_POST['lieu_depart']) || empty($_POST['date'])) {
            $error_form_recherche = true;
            echo '<pre>';
            var_dump('CovoituragesController.find().lieu_depart et date valorisés.');
            var_dump($_POST);
            echo '</pre>';
        }

        echo '<pre>';
        var_dump('CovoituragesController.find().calling $this->Covoiturage->recherche(lieu et date)..');
        echo '</pre>';
        $covoiturages = $this->Covoiturage->recherche($_POST['lieu_depart'], $_POST['date']);

        $covoiturages_lieu_ou_date = $this->Covoiturage->recherche_lieu_ou_date($_POST['lieu_depart'], $_POST['date']);

        // Récupération et application des filtres
        $filters = array(
            'energie' => isset($_POST['energie']) && is_array($_POST['energie']) ? $_POST['energie'] : array(),
            'prix_min' => isset($_POST['prix_min']) ? $_POST['prix_min'] : '',
            'prix_max' => isset($_POST['prix_max']) ? $_POST['prix_max'] : '',
            'duree_max' => isset($_POST['duree_max']) ? $_POST['duree_max'] : '',
            'score_min' => isset($_POST['score_min']) ? $_POST['score_min'] : ''
        );

        // Application des filtres aux résultats UNIQUEMENT si l'utilisateur a cliqué sur "Appliquer"
        // Le flag apply_filters est set dans le formulaire de filtres
        //if (isset($_POST['apply_filters']) && !empty(array_filter($filters))) {
        if (!empty(array_filter($filters))) {
            echo '<pre>';
            var_dump('CovoituragesController.find().applying filters to covoiturages..');
            echo '</pre>';
            $covoiturages = $this->applyFilters($covoiturages, $filters);
            $covoiturages_lieu_ou_date = $this->applyFilters($covoiturages_lieu_ou_date, $filters);
        }

        echo '<pre>';
        var_dump('CovoituragesController.find().nouvelle instantation new MyForm($_POST)');
        echo '</pre>';
        $form = new MyForm($_POST);

        echo '<pre>';
        var_dump('CovoituragesController.find().calling render()..');
        echo '</pre>';
        // ça appelle la page trajet (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render('covoiturages.trajet', compact('covoiturages', 'covoiturages_lieu_ou_date', 'form', 'error_form_recherche', 'filters'));
    }

    /**
     * Filtre les covoiturages selon les critères spécifiés
     * @param array $covoiturages Liste des covoiturages à filtrer
     * @param array $filters Filtres à appliquer (energie, prix_min, prix_max, duree_max, score_min)
     * @return array Covoiturages filtrés
     */
    private function applyFilters($covoiturages, $filters)
    {
        echo '<pre>';
        var_dump('CovoiturageModel.applyFilters() called with filters:');
        var_dump($filters);
        echo '</pre>';

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

        // Filtre par score minimum (NOTE: À adapter selon votre structure de base de données)
        if (!empty($filters['score_min'])) {
            $score_min = floatval($filters['score_min']);
            // Vous devrez ajouter la colonne score dans votre table covoiturage
            // $filtered = array_filter($filtered, function ($covoiturage) use ($score_min) {
            //     return floatval($covoiturage->score) >= $score_min;
            // });
        }

        return array_values($filtered); // Réinitialiser les clés du tableau
    }

    /**
     * Utilitaire pour supprimer les accents
     * @param string $str Chaîne à traiter
     * @return string Chaîne sans accents
     */
    private function removeAccents($str)
    {
        $str = (string) $str;
        $map = array(
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ý' => 'y',
            'ÿ' => 'y',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y'
        );
        return strtr($str, $map);
    }

    public function all()
    {
        echo '<pre>';
        var_dump('CovoituragesController.all() called.');
        echo '</pre>';

        //['posts'=>$post,'categories'=>$categories]
        //compact('posts','categories')

        $covoiturages = $this->Covoiturage->all();

        echo '<pre>';
        var_dump('CovoituragesController.all().calling render()..');
        echo '</pre>';

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.covoiturages',
            compact('covoiturages')

        );
    }

    // pour présenter à l'utilisateur un article spécifique 
    /*public function show()
    {
        echo '<pre>';
        var_dump('CovoituragesController.show().called.');
        echo '</pre>';

        $article = $this->Post->find($_GET['id']);

        if ($article === false) {
            $this->notFound();
        }

        $this->render('posts.article', compact('article',));
    }*/
}
