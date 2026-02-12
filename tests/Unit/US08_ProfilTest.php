<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\UtilisateurModel;
use NsCoreEcoride\Model\Model;

// Mocking App class for DB connection if needed, or assuming environment is set up.
// Since we are running in the same environment, we'll try to use the real App.
require_once __DIR__ . '/../../app/App.php'; 

/**
 * Tests unitaires pour le modèle Utilisateur.
 * Vérifie les préférences utilisateur et les mises à jour de profil.
 */
class US08_ProfilTest extends TestCase
{
    /** @var UtilisateurModel Modèle utilisateur pour les tests */
    protected $modele;
    
    /** @var int ID de l'utilisateur de test */
    protected $idUtilisateurTest;

    /**
     * Initialise le modèle et crée un utilisateur de test.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Debug : Require manuel pour vérifier le chemin du fichier
        if (!class_exists(UtilisateurModel::class)) {
             $chemin = __DIR__ . '/../../app/Model/UtilisateurModel.php';
             if (file_exists($chemin)) {
                 require_once $chemin;
             } else {
                 throw new \Exception("Fichier introuvable : " . $chemin);
             }
        }
        
        // Définir ROOT si non défini
        if (!defined('ROOT')) {
            define('ROOT', dirname(dirname(__DIR__)));
        }

        // Utiliser la vraie connexion DB
        $db = \App::getInstance()->getDb();
        $this->modele = new UtilisateurModel($db); 
        
        // Créer un utilisateur de test directement en BDD
        $this->modele->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('TestNom', 'TestPrenom', 'test.unit@mail.com', 'testpass', 'TestUnit', 2, 20)");
        $utilisateur = $this->modele->findByEmail('test.unit@mail.com');
        $this->idUtilisateurTest = $utilisateur->utilisateur_id;
    }

    /**
     * Nettoie les données de test après chaque test.
     * Supprime les préférences et l'utilisateur de test.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        if ($this->idUtilisateurTest) {
            $this->modele->clearPreferences($this->idUtilisateurTest);
            $this->modele->query("DELETE FROM utilisateur WHERE utilisateur_id = ?", [$this->idUtilisateurTest]);
        }
    }

    /**
     * Teste l'ajout et la récupération des préférences utilisateur.
     * 
     * @return void
     */
    public function testAjouterEtRecupererPreferences()
    {
        // Action
        $this->modele->addPreference($this->idUtilisateurTest, 'Fumeur');
        $this->modele->addPreference($this->idUtilisateurTest, 'Voyage test');

        // Assertion
        $preferences = $this->modele->getPreferences($this->idUtilisateurTest);
        $this->assertCount(2, $preferences);
        
        $libelles = array_map(function($p) { return $p->libelle; }, $preferences);
        $this->assertContains('Fumeur', $libelles);
        $this->assertContains('Voyage test', $libelles);
    }

    /**
     * Teste la suppression des préférences utilisateur.
     * 
     * @return void
     */
    public function testEffacerPreferences()
    {
        // Arrangement
        $this->modele->addPreference($this->idUtilisateurTest, 'TestPref');
        
        // Action
        $this->modele->clearPreferences($this->idUtilisateurTest);
        
        // Assertion
        $preferences = $this->modele->getPreferences($this->idUtilisateurTest);
        $this->assertCount(0, $preferences);
    }

    /**
     * Teste la mise à jour des informations utilisateur.
     * 
     * @return void
     */
    public function testMettreAJourUtilisateur()
    {
        // Action
        $this->modele->update($this->idUtilisateurTest, ['nom' => 'NomMisAJour']);
        
        // Assertion
        $utilisateur = $this->modele->find($this->idUtilisateurTest);
        $this->assertEquals('NomMisAJour', $utilisateur->nom);
    }
}

