<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\CovoiturageModel;
use NsAppEcoride\Model\UtilisateurModel;
use NsAppEcoride\Model\VoitureModel;

require_once __DIR__ . '/../../app/App.php';

/**
 * Tests unitaires pour la gestion des covoiturages.
 * Vérifie le démarrage et l'arrêt des trajets (changements de statut).
 */
class US11_CycleVieVoyageTest extends TestCase
{
    /** @var CovoiturageModel Modèle covoiturage pour les tests */
    protected $covoiturageModel;
    
    /** @var int ID de l'utilisateur de test */
    protected $idUtilisateurTest;
    
    /** @var int ID de la voiture de test */
    protected $idVoitureTest;
    
    /** @var int ID du covoiturage de test */
    protected $idCovoiturageTest;
    
    /** @var MysqlDatabase Connexion à la base de données */
    protected $db;

    /**
     * Initialise les données de test avant chaque test.
     * Crée un utilisateur, une voiture et un covoiturage de test.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Configuration de la connexion DB
        if (!class_exists(CovoiturageModel::class)) {
            require_once __DIR__ . '/../../app/Model/CovoiturageModel.php';
        }

        $this->db = \App::getInstance()->getDb();
        $this->covoiturageModel = new CovoiturageModel($this->db);

        // S'assurer que les nouveaux statuts existent
        $this->covoiturageModel->query("INSERT IGNORE INTO statut_covoiturage (statut_covoiturage_id, libelle) VALUES (4, 'en_cours'), (5, 'terminé')");

        // 1. Créer un utilisateur de test (Chauffeur) avec email unique
        $uniqueId = uniqid();
        $email = "chauffeur.test.$uniqueId@mail.com";
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('ChauffeurTest', 'Chauffeur', ?, 'pass', ?, 1, 20)", [$email, "ChauffeurTest_$uniqueId"]);
        $user = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = ?", [$email], true);
        $this->idUtilisateurTest = $user->utilisateur_id;

        // 2. Créer une voiture de test
        $this->covoiturageModel->query("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('VoitureTest', 'TT-123-RR', 'Essence', 1, ?)", [$this->idUtilisateurTest]);
        $car = $this->covoiturageModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'TT-123-RR'", [], true);
        $this->idVoitureTest = $car->voiture_id;

        // 3. Créer un covoiturage de test (Statut 2 = prévu)
        $this->covoiturageModel->query(
            "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
            VALUES ('2026-12-31', '10:00', 'Paris', 'Lyon', 2, 3, 20.0, ?)",
            [$this->idVoitureTest]
        );
        $trip = $this->covoiturageModel->query("SELECT covoiturage_id FROM covoiturage WHERE voiture_id = ? AND date_depart = '2026-12-31'", [$this->idVoitureTest], true);
        $this->idCovoiturageTest = $trip->covoiturage_id;
    }

    /**
     * Nettoie les données de test après chaque test.
     * Supprime le covoiturage, la voiture et l'utilisateur créés.
     * 
     * @return void
     */
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

    /**
     * Teste le démarrage d'un covoiturage (passage au statut 4 = en_cours).
     * 
     * @return void
     */
    public function testDemarrerCovoiturage()
    {
        // Action : Démarrer le trajet (Statut 4)
        $result = $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 4);

        // Vérification
        $this->assertTrue($result, "La mise à jour au statut 4 a échoué");
        
        $trip = $this->covoiturageModel->find($this->idCovoiturageTest);
        // $this->assertEquals(4, $trip->statut_covoiturage_id, "Le statut devrait être 4 (en_cours)");
        
        $this->assertEquals('en_cours', $trip->statut, "Le libellé du statut devrait être 'en_cours'");
    }

    /**
     * Teste l'arrêt d'un covoiturage (passage au statut 5 = terminé).
     * 
     * @return void
     */
    public function testArreterCovoiturage()
    {
        // Préparation : Mettre en en_cours d'abord
        $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 4);

        // Action : Arrêter le trajet (Statut 5)
        $result = $this->covoiturageModel->updateStatut($this->idCovoiturageTest, 5);

        // Vérification
        $this->assertTrue($result);
        $trip = $this->covoiturageModel->find($this->idCovoiturageTest);
        $this->assertEquals('terminé', $trip->statut, "Le libellé du statut devrait être 'terminé'");
    }
}
