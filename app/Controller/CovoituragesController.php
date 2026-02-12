<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

/**
 * Contrôleur pour la gestion des covoiturages.
 * Gère la participation, le démarrage et l'arrêt des trajets.
 */
class CovoituragesController extends AppController
{

    /**
     * Constructeur du contrôleur de covoiturages.
     * Initialise le modèle Covoiturage.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel('Covoiturage');
    }

    /**
     * Affiche la page d'accueil des covoiturages.
     * 
     * @return void Affiche la vue covoiturages.index
     */
    public function index()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.index',
            []
        );
    }

    /**
     * Affiche tous les covoiturages disponibles.
     * 
     * @return void Affiche la vue covoiturages.covoiturage avec la liste complète
     */
    public function all()
    {
        // Récupérer tous les covoiturages
        $covoiturages = $this->Covoiturage->all();
        
        $this->render('covoiturages.covoiturage', compact('covoiturages'));
    }



    /**
     * Action pour participer à un covoiturage.
     * Gère la déduction de crédits, l'enregistrement de la participation
     * et la mise à jour du nombre de places disponibles.
     * 
     * @return void Envoie une réponse JSON avec le résultat de l'opération
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

        // Vérifier que le covoiturage existe et a des places disponibles
        $covoiturage = $this->Covoiturage->find($covoiturage_id);
        if (!$covoiturage) {
            $this->jsonResponse(['success' => false, 'message' => 'Covoiturage non trouvé']);
            return;
        }

        // Vérifier que l'utilisateur a assez de crédits (minimum 2)
        // Vérifier que l'utilisateur a assez de crédits
        if ($utilisateur->credit < 2) {
            $this->jsonResponse(['success' => false, 'message' => "Crédits insuffisants. Vous avez besoin de 2 crédits pour participer"]);
            return;
        }

        // Commencer la transaction pour garantir la cohérence des données
        try {
            // 1. Déduire les crédits de l'utilisateur
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

            // 4. Envoyer l'email de confirmation
            try {
                require_once ROOT . '/app/Service/Mailer.php';
                $mailer = new \NsAppEcoride\Service\Mailer();

                $subject = "Confirmation de réservation - EcoRide";
                $body = "
                    <h1>Réservation Confirmée !</h1>
                    <p>Bonjour " . htmlspecialchars($utilisateur->pseudo) . ",</p>
                    <p>Votre réservation pour le trajet <strong>" . htmlspecialchars($covoiturage->lieu_depart) . "</strong> vers <strong>" . htmlspecialchars($covoiturage->lieu_arrivee) . "</strong> est confirmée.</p>
                    <p><strong>Date :</strong> " . htmlspecialchars($covoiturage->date_depart) . " à " . htmlspecialchars(substr($covoiturage->heure_depart, 0, 5)) . "</p>
                    <p><strong>Prix :</strong> " . htmlspecialchars($covoiturage->prix_personne) . " crédits (2 crédits déduits)</p>
                    <p>Merci de voyager avec EcoRide !</p>
                ";
                
                $mailer->send($utilisateur->email, $subject, $body);

            } catch (\Throwable $e) {
                // On loggue l'erreur mais on ne bloque pas la réponse positive car la réservation est faite
                error_log("Erreur envoi email confirmation : " . $e->getMessage());
            }

            // 5. Réponse positive
            $this->jsonResponse([
                'success' => true,
                'message' => 'Vous avez réservé votre place avec succès ! 2 crédits ont été déduits de votre compte'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la réservation : ' . $e->getMessage()]);
        }
    }

    /**
     * Démarre un covoiturage (passage au statut 4: en_cours).
     * Vérifie que l'utilisateur est bien le conducteur du trajet.
     * 
     * @return void Redirige vers la page d'édition du trajet
     */
    public function start()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;
        
        // Vérifier que l'utilisateur est bien le conducteur du trajet
        $covoiturage = $this->Covoiturage->findWithDetails($covoiturage_id);
        if (!$covoiturage || $covoiturage->utilisateur_id != $_SESSION['auth']) {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $this->Covoiturage->updateStatut($covoiturage_id, 4); // 4 = en_cours
        
        // Redirection vers l'édition du trajet
        $this->redirect('index.php?p=trajets.edit&id=' . $covoiturage_id);
    }

    /**
     * Termine un covoiturage (passage au statut 5: terminé).
     * Envoie des emails de validation aux participants.
     * 
     * @return void Redirige vers le profil utilisateur
     */
    public function stop()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;

        // Vérifier que l'utilisateur est bien le conducteur du trajet
        $covoiturage = $this->Covoiturage->findWithDetails($covoiturage_id);
        if (!$covoiturage || $covoiturage->utilisateur_id != $_SESSION['auth']) {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $this->Covoiturage->updateStatut($covoiturage_id, 5); // 5 = terminé
        
        // Envoyer un mail aux participants (Simulation)
        $this->sendValidationEmails($covoiturage_id);

        // Redirection vers le profil
        $this->redirect('index.php?p=utilisateurs.profile.index');
    }

    /**
     * Envoie les emails de validation aux participants d'un covoiturage terminé.
     * Enregistre les envois dans un fichier log.
     * 
     * @param int $covoiturage_id ID du covoiturage terminé
     * @return void
     */
    private function sendValidationEmails($covoiturage_id)
    {
        // Récupérer les participants réels
        $participants = $this->Covoiturage->getParticipants($covoiturage_id);
        
        // Création du fichier log comme demandé
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
     * Envoie une réponse JSON et termine l'exécution.
     * 
     * @param array $data Données à encoder en JSON et envoyer
     * @return void
     */
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
