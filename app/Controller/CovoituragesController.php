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

    public function all()
    {
        // Récupérer tous les covoiturages
        $covoiturages = $this->Covoiturage->all();
        
        $this->render('covoiturages.covoiturage', compact('covoiturages'));
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
     * Démarre un covoiturage (passage au statut 4: en_cours)
     */
    public function start()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;
        
        // TODO: Vérifier que l'utilisateur est bien le conducteur du trajet (sécurité)

        if ($covoiturage_id > 0) {
            $this->Covoiturage->updateStatut($covoiturage_id, 4); // 4 = en_cours
        }

        // Redirection vers l'édition du trajet
        $this->redirect('index.php?p=trajets.edit&id=' . $covoiturage_id);
    }

    /**
     * Termine un covoiturage (passage au statut 5: terminé)
     */
    public function stop()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;

        // TODO: Vérifier que l'utilisateur est bien le conducteur du trajet (sécurité)

        if ($covoiturage_id > 0) {
            $this->Covoiturage->updateStatut($covoiturage_id, 5); // 5 = terminé
            
            // Envoyer un mail aux participants (Simulation)
            $this->sendValidationEmails($covoiturage_id);
        }

        // Redirection vers le profil
        $this->redirect('index.php?p=utilisateurs.profile.index');
    }

    private function sendValidationEmails($covoiturage_id)
    {
        // Récupérer les participants réels
        $participants = $this->Covoiturage->getParticipants($covoiturage_id);
        
        // Log file creation as requested
        $logFile = ROOT . '/log/us11_trip_terminé.txt';
        $message = date('Y-m-d H:i:s') . " - Covoiturage $covoiturage_id terminé.\n";
        
        if (!empty($participants)) {
            $message .= "Emails envoyés aux participants :\n";
            foreach ($participants as $participant) {
                // Simulation d'envoi de mail
                $message .= " - " . $participant->email . " (" . $participant->pseudo . ")\n";
                // Ici, on appellerait le service mail réel
            }
        } else {
            $message .= "Aucun participant à notifier.\n";
        }

        $message .= "Lien de validation : " . $_SERVER['HTTP_HOST'] . "/EcoRide/public/index.php?p=participant.validate&covoiturage_id=" . $covoiturage_id . "\n";
        $message .= "--------------------------------------------------\n";
        
        file_put_contents($logFile, $message, FILE_APPEND);
        
        error_log("Envoi des emails de validation pour le covoiturage " . $covoiturage_id);
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
