<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\VoitureModel;
use NsAppEcoride\Model\UtilisateurModel;

require_once __DIR__ . '/../../app/App.php';

/**
 * Tests unitaires pour la User Story 08 (Gestion des voitures).
 * Vérifie l'ajout, la modification, la suppression et la récupération des voitures.
 */
class US08_VoitureTest extends TestCase
{
    protected $voitureModel;
    protected $utilisateurModel;
    protected $db;
    protected $idUtilisateurTest;
    protected $idsVoituresTest = [];

    protected function setUp(): void
    {
        $this->db = \App::getInstance()->getDb();
        $this->voitureModel = new VoitureModel($this->db);
        $this->utilisateurModel = new UtilisateurModel($this->db);

        // Créer un utilisateur de test avec email unique
        $uniqueId = uniqid();
        $email = "test.voiture.$uniqueId@mail.com";
        $this->utilisateurModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('TestVoiture', 'User', ?, 'pass', ?, 2, 20)", [$email, "TestVoiture_$uniqueId"]);
        $user = $this->utilisateurModel->findByEmail($email);
        $this->idUtilisateurTest = $user->utilisateur_id;

        // Assurez-vous qu'une marque existe (ID 1)
        $this->voitureModel->query("INSERT IGNORE INTO marque (marque_id, libelle) VALUES (1, 'Peugeot')");
    }

    protected function tearDown(): void
    {
        // Supprimer les voitures de test
        foreach ($this->idsVoituresTest as $id) {
            $this->voitureModel->query("DELETE FROM voiture WHERE voiture_id = ?", [$id]);
        }
        
        // Supprimer l'utilisateur de test
        if ($this->idUtilisateurTest) {
            $this->utilisateurModel->query("DELETE FROM utilisateur WHERE utilisateur_id = ?", [$this->idUtilisateurTest]);
        }
    }

    public function testAjouterVoiture()
    {
        $data = [
            'modele' => '208',
            'immatriculation' => 'AB-123-CD',
            'energie' => 'Essence',
            'couleur' => 'Rouge',
            'date_premiere_immatriculation' => '2020-01-01',
            'marque_id' => 1,
            'utilisateur_id' => $this->idUtilisateurTest
        ];

        $result = $this->voitureModel->create($data);
        $this->assertTrue($result, "La création de la voiture devrait réussir");

        // Récupérer l'ID pour le nettoyage
        $voiture = $this->voitureModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'AB-123-CD' AND utilisateur_id = ?", [$this->idUtilisateurTest], true);
        $this->assertNotNull($voiture);
        $this->idsVoituresTest[] = $voiture->voiture_id;
    }

    public function testRecupererVoituresUtilisateur()
    {
        // Créer une voiture
        $data = [
            'modele' => '308',
            'immatriculation' => 'EF-456-GH',
            'energie' => 'Diesel',
            'couleur' => 'Noir',
            'date_premiere_immatriculation' => '2019-01-01',
            'marque_id' => 1,
            'utilisateur_id' => $this->idUtilisateurTest
        ];
        $this->voitureModel->create($data);
        $voiture = $this->voitureModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'EF-456-GH'", [], true);
        $this->idsVoituresTest[] = $voiture->voiture_id;

        // Récupérer
        $voitures = $this->voitureModel->getVoituresByUserId($this->idUtilisateurTest);
        $this->assertCount(1, $voitures);
        $this->assertEquals('308', $voitures[0]->modele);
    }

    public function testModifierVoiture()
    {
        // Créer
        $data = [
            'modele' => '508',
            'immatriculation' => 'IJ-789-KL',
            'energie' => 'Hybride',
            'couleur' => 'Blanc',
            'date_premiere_immatriculation' => '2021-01-01',
            'marque_id' => 1,
            'utilisateur_id' => $this->idUtilisateurTest
        ];
        $this->voitureModel->create($data);
        $voiture = $this->voitureModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'IJ-789-KL'", [], true);
        $this->idsVoituresTest[] = $voitureId = $voiture->voiture_id;

        // Modifier
        $updateData = $data;
        $updateData['couleur'] = 'Bleu';
        $result = $this->voitureModel->update($voitureId, $updateData);
        $this->assertTrue($result);

        // Vérifier
        $updatedVoiture = $this->voitureModel->query("SELECT couleur FROM voiture WHERE voiture_id = ?", [$voitureId], true);
        $this->assertEquals('Bleu', $updatedVoiture->couleur);
    }

    public function testSupprimerVoiture()
    {
        // Créer
        $data = [
            'modele' => 'Clio',
            'immatriculation' => 'MN-012-OP',
            'energie' => 'Essence',
            'couleur' => 'Gris',
            'date_premiere_immatriculation' => '2018-01-01',
            'marque_id' => 1,
            'utilisateur_id' => $this->idUtilisateurTest
        ];
        $this->voitureModel->create($data);
        $voiture = $this->voitureModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'MN-012-OP'", [], true);
        $voitureId = $voiture->voiture_id;
        // On ne l'ajoute pas à idsVoituresTest car on va le supprimer dans le test

        // Supprimer
        $result = $this->voitureModel->deleteCar($voitureId, $this->idUtilisateurTest);
        $this->assertTrue($result);

        // Vérifier suppression
        $check = $this->voitureModel->query("SELECT count(*) as count FROM voiture WHERE voiture_id = ?", [$voitureId], true);
        $this->assertEquals(0, $check->count);
    }
}
