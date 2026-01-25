<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\CovoiturageModel;
use NsAppEcoride\Model\UtilisateurModel;
use NsAppEcoride\Model\VoitureModel;

require_once __DIR__ . '/../../app/App.php';

class CovoiturageTest extends TestCase
{
    protected $covoiturageModel;
    protected $idUtilisateurTest;
    protected $idVoitureTest;
    protected $idCovoiturageTest;
    protected $db;

    protected function setUp(): void
    {
        // Setup DB connection
        if (!class_exists(CovoiturageModel::class)) {
            require_once __DIR__ . '/../../app/Model/CovoiturageModel.php';
        }

        $this->db = \App::getInstance()->getDb();
        $this->covoiturageModel = new CovoiturageModel($this->db);

        // Ensure new statuses exist
        $this->covoiturageModel->query("INSERT IGNORE INTO statut_covoiturage (statut_covoiturage_id, libelle) VALUES (4, 'en_cours'), (5, 'terminé')");

        // 1. Create a Test User (Driver)
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('DriverTest', 'Driver', 'driver.test@mail.com', 'pass', 'DriverTest', 1, 20)");
        $user = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = 'driver.test@mail.com'", [], true);
        $this->idUtilisateurTest = $user->utilisateur_id;

        // 2. Create a Test Car
        $this->covoiturageModel->query("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('TestCar', 'TT-123-RR', 'Essence', 1, ?)", [$this->idUtilisateurTest]);
        $car = $this->covoiturageModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'TT-123-RR'", [], true);
        $this->idVoitureTest = $car->voiture_id;

        // 3. Create a Test Carpool (Status 2 = prévu)
        $this->covoiturageModel->query(
            "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
            VALUES ('2026-12-31', '10:00', 'Paris', 'Lyon', 2, 3, 20.0, ?)",
            [$this->idVoitureTest]
        );
        $trip = $this->covoiturageModel->query("SELECT covoiturage_id FROM covoiturage WHERE voiture_id = ? AND date_depart = '2026-12-31'", [$this->idVoitureTest], true);
        $this->idCovoiturageTest = $trip->covoiturage_id;
    }

    protected function tearDown(): void
    {
        if ($this->idCovoiturageTest) {
            $this->covoiturageModel->query("DELETE FROM covoiturage WHERE covoiturage_id = ?", [$this->idCovoiturageTest]);
        }
        if ($this->idVoitureTest) {
            $this->covoiturageModel->query("DELETE FROM voiture WHERE voiture_id = ?", [$this->idVoitureTest]);
        }
        if ($this->idUtilisateurTest) {
            $this->covoiturageModel->query("DELETE FROM utilisateur WHERE utilisateur_id = ?", [$this->idUtilisateurTest]);
        }
    }

    public function testStartCovoiturage()
    {
        // Act: Start trip (Status 4)
        $result = $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 4);

        // Assert
        $this->assertTrue($result, "Update to status 4 failed");
        
        $trip = $this->covoiturageModel->find($this->idCovoiturageTest);
        // $this->assertEquals(4, $trip->statut_covoiturage_id, "Status should be 4 (en_cours)");
        
        $this->assertEquals('en_cours', $trip->statut, "Status libelle should be 'en_cours'");
    }

    public function testStopCovoiturage()
    {
        // Arrange: Set to en_cours first
        $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 4);

        // Act: Stop trip (Status 5)
        $result = $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 5);

        // Assert
        $this->assertTrue($result);
        $trip = $this->covoiturageModel->find($this->idCovoiturageTest);
        $this->assertEquals('terminé', $trip->statut, "Status libelle should be 'terminé'");
    }
}
