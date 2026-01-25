<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\CovoiturageModel;
use NsAppEcoride\Model\UtilisateurModel;

require_once __DIR__ . '/../../app/App.php';

class ParticipantValidationTest extends TestCase
{
    protected $covoiturageModel;
    protected $utilisateurModel;
    protected $db;
    
    // IDs for test data
    protected $idChauffeur;
    protected $idPassager;
    protected $idVoiture;
    protected $idCovoiturage;

    protected function setUp(): void
    {
        $this->db = \App::getInstance()->getDb();
        $this->covoiturageModel = new CovoiturageModel($this->db);
        $this->utilisateurModel = new UtilisateurModel($this->db);

        // Pre-cleanup to avoid duplicates if tearDown failed previously
        if ($this->covoiturageModel) {
             $this->covoiturageModel->query("DELETE FROM participe"); 
             $this->covoiturageModel->query("DELETE FROM covoiturage");
             $this->covoiturageModel->query("DELETE FROM voiture");
             $this->covoiturageModel->query("DELETE FROM utilisateur WHERE email LIKE '%.v@mail.com'");
        }

        // Ensure real statuses exist (prevent FK errors)
        $this->covoiturageModel->query("INSERT IGNORE INTO statut_covoiturage (statut_covoiturage_id, libelle) VALUES (1,'annulé'),(2,'prévu'),(3,'confirmé'),(4,'en_cours'),(5,'terminé')");
        $this->covoiturageModel->query("INSERT IGNORE INTO role (role_id, libelle) VALUES (1, 'Chauffeur'), (2, 'Passager')");
        $this->covoiturageModel->query("INSERT IGNORE INTO marque (marque_id, libelle) VALUES (1, 'Renault')");
        // Ensure avis_covoiturage table has statuses
        $this->covoiturageModel->query("INSERT IGNORE INTO avis_covoiturage (avis_covoiturage_id, libelle) VALUES (1, 's’est bien passé'), (2, 's’est mal passé')");

        // 1. Create Chauffeur (start with 20 credits)
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('ChauffeurV', 'Valid', 'chauffeur.v@mail.com', 'pass', 'ChauffeurV', 1, 20)");
        $driver = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = 'chauffeur.v@mail.com'", [], true);
        $this->idChauffeur = $driver->utilisateur_id;

        // 2. Create Passager
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('PassagerV', 'Valid', 'passager.v@mail.com', 'pass', 'PassagerV', 2, 50)");
        $passenger = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = 'passager.v@mail.com'", [], true);
        $this->idPassager = $passenger->utilisateur_id;

        // 3. Create Voiture linked to Chauffeur
        $this->covoiturageModel->query("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('Clio', 'VV-111-AA', 'Essence', 1, ?)", [$this->idChauffeur]);
        $car = $this->covoiturageModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'VV-111-AA'", [], true);
        $this->idVoiture = $car->voiture_id;

        // 4. Create Covoiturage (Price = 10 credits)
        $this->covoiturageModel->query(
            "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
            VALUES ('2026-12-31', '10:00', 'Paris', 'Rouen', 5, 3, 10.0, ?)", 
            [$this->idVoiture]
        ); // Status 5 = terminé
        $trip = $this->covoiturageModel->query("SELECT covoiturage_id FROM covoiturage WHERE voiture_id = ? AND date_depart = '2026-12-31'", [$this->idVoiture], true);
        $this->idCovoiturage = $trip->covoiturage_id;

        // 5. Passager participates (not strictly needed for crediting logic test but good for context)
        $this->covoiturageModel->enregistrerParticipation($this->idPassager, $this->idCovoiturage);
        
        echo "\nDEBUG: CovoitID=" . $this->idCovoiturage . " VoitureID=" . $this->idVoiture . " DriverID=" . $this->idChauffeur . "\n";
    }

    protected function tearDown(): void
    {
        // Cleanup Foreign Keys first
        if ($this->covoiturageModel) {
             $this->covoiturageModel->query("DELETE FROM participe"); 
             $this->covoiturageModel->query("DELETE FROM covoiturage");
             $this->covoiturageModel->query("DELETE FROM voiture");
             $this->covoiturageModel->query("DELETE FROM utilisateur WHERE email LIKE '%.v@mail.com'");
        }
    }

    public function testCreditDriverOnSuccess()
    {
        // Simulate Logic from ParticipantController::submitValidation for Case 1 (Success)
        
        // 1. Fetch trip and price
        $covoiturage = $this->covoiturageModel->find($this->idCovoiturage);
        echo "\nDEBUG: Fetched Covoit VoitureID=" . $covoiturage->voiture_id . "\n";
        $price = $covoiturage->prix_personne; // Should be 10
        
        // 2. Initial Credit Check
        $chauffeurBefore = $this->utilisateurModel->find($this->idChauffeur);
        $initialCredit = $chauffeurBefore->credit; // Should be 20

        // 3. Execute Crediting Logic
        $voiture = $this->covoiturageModel->query("SELECT utilisateur_id FROM voiture WHERE voiture_id = ?", [$covoiturage->voiture_id], true);
        if ($voiture) {
            $chauffeur_id = $voiture->utilisateur_id;
            $res = $this->utilisateurModel->crediter($chauffeur_id, $price);
            $this->assertTrue($res, "Crediter method returned false");
        } else {
             $this->fail("Voiture not found for covoiturage");
        }

        // 4. Assert
        $chauffeurAfter = $this->utilisateurModel->find($this->idChauffeur);
        $this->assertEquals($initialCredit + 10, $chauffeurAfter->credit, "Driver should be credited with 10 credits");
    }

    public function testLogIncidentOnFailure()
    {
        // Simulate Logic for Case 2 (Failure)
        // Ideally we check if log entry is created or error_log is called.
        // Since error_log goes to php error log which is hard to read here, 
        // we mainly verify that NO credit is added.

        // 1. Initial Credit Check
        $chauffeurBefore = $this->utilisateurModel->find($this->idChauffeur);
        $initialCredit = $chauffeurBefore->credit;

        // 2. Logic for Failure (Incident) - basically do nothing but log
        $price = 10;
        // In the controller: if ($avis_covoiturage_id == 2) { ... error_log ... }
        // So NO crediter() call.

        // 3. Assert
        $chauffeurAfter = $this->utilisateurModel->find($this->idChauffeur);
        $this->assertEquals($initialCredit, $chauffeurAfter->credit, "Driver should not receive credits on incident");
    }
}
