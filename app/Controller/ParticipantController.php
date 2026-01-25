<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class ParticipantController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Covoiturage');
        $this->loadModel('Utilisateur');
    }

    /**
     * Affiche la page de validation pour un utilisateur
     */
    public function validate()
    {
        // TODO: En prod, vérifier le token de sécurité reçu par mail
        
        $covoiturage_id = isset($_GET['covoiturage_id']) ? intval($_GET['covoiturage_id']) : 0;
        $utilisateur_id = $_SESSION['auth'] ?? 0;

        if ($covoiturage_id <= 0 || $utilisateur_id <= 0) {
            $this->redirect('index.php?p=utilisateurs.login');
        }

        // Vérifier que l'utilisateur a bien participé à ce covoiturage
        // $this->Covoiturage->hasUserReserved($covoiturage_id, $utilisateur_id);

        $covoiturage = $this->Covoiturage->findWithDetails($covoiturage_id);

        if (!$covoiturage) {
            die("Covoiturage non trouvé");
        }

        $this->render('participant.validate', compact('covoiturage'));
    }

    /**
     * Traite la validation du covoiturage
     */
    public function submitValidation()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?p=utilisateurs.profile.index');
        }

        $covoiturage_id = isset($_POST['covoiturage_id']) ? intval($_POST['covoiturage_id']) : 0;
        $avis_covoiturage_id = isset($_POST['avis_covoiturage_id']) ? intval($_POST['avis_covoiturage_id']) : 0; // 1=Bien, 2=Mal
        $utilisateur_id = $_SESSION['auth'] ?? 0;

        if ($covoiturage_id <= 0 || $utilisateur_id <= 0) {
            $this->redirect('index.php?p=utilisateurs.login');
        }

        // Logic de validation
        if ($avis_covoiturage_id == 1) {
            // "s’est bien passé"
            // Créditer le chauffeur
            $covoiturage = $this->Covoiturage->find($covoiturage_id);
            if ($covoiturage) {
                // On crédite le montant du trajet
                
                // On assume que le chauffeur est l'utilisateur lié à la voiture du trajet
                $voiture = $this->Covoiturage->query("SELECT utilisateur_id FROM voiture WHERE voiture_id = ?", [$covoiturage->voiture_id], true);
                if ($voiture) {
                    $chauffeur_id = $voiture->utilisateur_id;
                    $this->Utilisateur->crediter($chauffeur_id, $covoiturage->prix_personne); 
                }
            }
        } elseif ($avis_covoiturage_id == 2) {
            // "s’est mal passé"
            // Ouvrir un incident (Log, Table spécifique, ou juste mail admin)
            // Pour l'instant on ne crédite pas le chauffeur.
            $commentaire = $_POST['commentaire'] ?? '';
            // TODO: Enregistrer l'incident
            error_log("Incident signalé pour covoiturage $covoiturage_id par user $utilisateur_id : $commentaire");
        }

        // Enregistrer l'avis sur le covoiturage (pas l'avis texte, mais le statut "bien/mal")
        // On pourrait ajouter une colonne avis_covoiturage_id dans la table participe pour chaque user ?
        // Mais la table covoiturage a une colonne avis_covoiturage_id (unique ? Pour le trajet global ?)
        // US11 dit: "Les participants ... indiquer de valider que tout s’est bien passé."
        // Si c'est stocké dans `covoiturage.avis_covoiturage_id`, ça écrase pour tout le monde ?
        // Supposons que ça met à jour le statut global du covoiturage si c'est ce que la DB demande.
        
        // Update covoiturage table if that's where it is stored
        if ($avis_covoiturage_id > 0) {
            $this->Covoiturage->query("UPDATE covoiturage SET avis_covoiturage_id = ? WHERE covoiturage_id = ?", [$avis_covoiturage_id, $covoiturage_id]);
        }

        // Redirection avec succès
        // $this->redirect('index.php?p=utilisateurs.profile.index');
        // Afficher un message de confirmation
        echo "Merci pour votre retour.";
    }
}
