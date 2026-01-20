<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class CovoituragesController extends AppController
{

    public function __construct()
    {
        parent::__construct();

        $this->loadModel('Covoiturage');
    }

    public function index()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.index',
            []
        );
    }

    public function show()
    {
        /*Pour la recherche d'itinéraire, la recherche se basera sur la ville ainsi que la date. */
        $error_form_recherche = false;

        // Vérifier si c'est une recherche POST
        $is_post_search = !empty($_POST['lieu_depart']) && !empty($_POST['date']);

        if ($is_post_search) {
            // **NOUVEAU SEARCH POST** : Stocker les critères dans la session
            $_SESSION['search_criteria'] = array(
                'lieu_depart' => $_POST['lieu_depart'],
                'date' => $_POST['date'],
                'filters' => array(
                    'energie' => isset($_POST['energie']) && is_array($_POST['energie']) ? $_POST['energie'] : array(),
                    'prix_min' => isset($_POST['prix_min']) ? $_POST['prix_min'] : '',
                    'prix_max' => isset($_POST['prix_max']) ? $_POST['prix_max'] : '',
                    'duree_max' => isset($_POST['duree_max']) ? $_POST['duree_max'] : '',
                    'score_min' => isset($_POST['score_min']) ? $_POST['score_min'] : ''
                )
            );

            $lieu_depart = $_POST['lieu_depart'];
            $date = $_POST['date'];
            $filters = $_SESSION['search_criteria']['filters'];
        } elseif (isset($_SESSION['search_criteria'])) {
            // **RETOUR SANS POST** : Récupérer les critères depuis la session
            $lieu_depart = $_SESSION['search_criteria']['lieu_depart'];
            $date = $_SESSION['search_criteria']['date'];
            $filters = $_SESSION['search_criteria']['filters'];
        } else {
            // **PREMIER ACCÈS** : Pas de critères, afficher formulaire vide
            $error_form_recherche = true;
            $lieu_depart = '';
            $date = '';
            $filters = array(
                'energie' => array(),
                'prix_min' => '',
                'prix_max' => '',
                'duree_max' => '',
                'score_min' => ''
            );
        }

        // Récupérer les données si on a les critères
        if (!$error_form_recherche) {
            $covoiturages = $this->Covoiturage->recherche($lieu_depart, $date);
            $covoiturages_lieu_ou_date = $this->Covoiturage->recherche_lieu_ou_date($lieu_depart, $date);

            // Application des filtres
            if (!empty(array_filter($filters))) {
                $covoiturages = $this->applyFilters($covoiturages, $filters);
                $covoiturages_lieu_ou_date = $this->applyFilters($covoiturages_lieu_ou_date, $filters);
            }
        } else {
            $covoiturages = array();
            $covoiturages_lieu_ou_date = array();
        }

        $form = new MyForm($_POST);

        // Charger les informations de l'utilisateur courant si connecté
        $utilisateur_courant = null;
        if (!empty($_SESSION['auth'])) {
            $this->loadModel('Utilisateur');
            $utilisateur_courant = $this->Utilisateur->find($_SESSION['auth']);
        }

        // Récupérer les participations de l'utilisateur connecté
        $participations_utilisateur = array();
        if (!empty($_SESSION['auth'])) {
            $participations = $this->Covoiturage->getParticipationsForUser($_SESSION['auth']);
            if ($participations) {
                foreach ($participations as $participation) {
                    $participations_utilisateur[$participation->covoiturage_id] = true;
                }
            }
        }

        // Normaliser l'énergie pour chaque covoiturage
        $energie_normalized_map = array();
        foreach (array_merge($covoiturages, $covoiturages_lieu_ou_date) as $covoiturage) {
            $energie_normalized_map[$covoiturage->covoiturage_id] = strtolower($this->removeAccents($covoiturage->energie ?? ''));
        }

        // ça appelle la page trajet (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render('covoiturages.trajet', compact('covoiturages', 'covoiturages_lieu_ou_date', 'form', 'error_form_recherche', 'filters', 'energie_normalized_map', 'utilisateur_courant', 'participations_utilisateur'));
    }

    /**
     * Filtre les covoiturages selon les critères spécifiés
     * @param array $covoiturages Liste des covoiturages à filtrer
     * @param array $filters Filtres à appliquer (energie, prix_min, prix_max, duree_max, score_min)
     * @return array Covoiturages filtrés
     */
    private function applyFilters($covoiturages, $filters)
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
        $covoiturages = $this->Covoiturage->all();

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render( 
            'covoiturages.covoiturage',
            compact('covoiturages')

        );
    }



    public function detail()
    {
        // Vérifier si l'ID du covoiturage est fourni
        if (empty($_GET['id'])) {
            // Rediriger vers la liste des trajets si pas d'ID
            header('Location: index.php?p=trajet');
            exit;
        }

        $covoiturage_id = intval($_GET['id']);

        // Récupérer les détails complets du covoiturage
        $covoiturage = $this->Covoiturage->findWithDetails($covoiturage_id);

        // Vérifier que le covoiturage existe
        if (!$covoiturage) {
            // Rediriger si non trouvé
            header('Location: index.php?p=trajet');
            exit;
        }

        // Normaliser l'énergie pour la comparaison
        $energie_normalized = strtolower($this->removeAccents($covoiturage->energie ?? ''));

        // Rendu du template complet (pas modal) avec les détails
        $this->render('covoiturages.trajet_detail', compact('covoiturage', 'energie_normalized'));
    }

    /**
     * Action pour participer à un covoiturage
     * Gère la déduction de crédits et l'enregistrement de la participation
     */
    public function participer()
    {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        // Récupérer l'ID du covoiturage
        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;

        if (empty($covoiturage_id)) {
            $this->jsonResponse(['success' => false, 'message' => 'Covoiturage non trouvé']);
            return;
        }

        // Vérifier si l'utilisateur est connecté
        if (empty($_SESSION['auth'])) {
            // Stocker l'ID du covoiturage et rediriger vers la connexion
            $_SESSION['covoiturage_reserve_id'] = $covoiturage_id;
            $this->jsonResponse(['success' => false, 'redirect' => 'index.php?p=utilisateurs.login', 'message' => 'Vous devez être connecté pour réserver']);
            return;
        }

        $utilisateur_id = $_SESSION['auth'];

        // Charger le modèle Utilisateur
        $this->loadModel('Utilisateur');

        // Récupérer les informations de l'utilisateur
        $utilisateur = $this->Utilisateur->find($utilisateur_id);

        if (!$utilisateur) {
            $this->jsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
            return;
        }

        // Vérifier que l'utilisateur a assez de crédits (minimum 2)
        if ($utilisateur->credit < 2) {
            $this->jsonResponse(['success' => false, 'message' => 'Crédits insuffisants. Vous avez besoin de 2 crédits pour participer']);
            return;
        }

        // Vérifier que le covoiturage existe et a des places disponibles
        $covoiturage = $this->Covoiturage->find($covoiturage_id);
        if (!$covoiturage) {
            $this->jsonResponse(['success' => false, 'message' => 'Covoiturage non trouvé']);
            return;
        }

        // Commencer la transaction pour garantir la cohérence des données
        try {
            // 1. Déduire les crédits de l'utilisateur (2 crédits)
            $credit_deduit = $this->Utilisateur->deduireCredit($utilisateur_id, 2);

            if (!$credit_deduit) {
                $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la déduction des crédits']);
                return;
            }

            // 2. Enregistrer la participation dans la table participe
            $participation_enregistree = $this->Covoiturage->enregistrerParticipation($utilisateur_id, $covoiturage_id);

            if (!$participation_enregistree) {
                // Si l'enregistrement échoue, on affiche un message approprié
                // (vérifier que l'utilisateur n'est pas déjà inscrit)
                $this->jsonResponse(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce covoiturage']);
                return;
            }

            // 3. Déduire une place du covoiturage
            $place_deduite = $this->Covoiturage->deduirePlace($covoiturage_id);

            if (!$place_deduite) {
                $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la mise à jour du nombre de places']);
                return;
            }

            // 4. Réponse positive
            $this->jsonResponse([
                'success' => true,
                'message' => 'Vous avez réservé votre place avec succès ! 2 crédits ont été déduits de votre compte'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la réservation : ' . $e->getMessage()]);
        }
    }

    /**
     * Envoie une réponse JSON
     * @param array $data Données à envoyer
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
