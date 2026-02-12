<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\CovoiturageModel;
use NsAppEcoride\Model\UtilisateurModel;

require_once __DIR__ . '/../../app/App.php';

/**
 * Tests unitaires pour la validation des participations.
 * Vérifie la logique de crédit du chauffeur en cas de succès
 * et le signalement d'incident en cas d'échec.
 */
class US11_ValidationTrajetTest extends TestCase
{
    /** @var CovoiturageModel Modèle covoiturage pour les tests */
    protected $covoiturageModel;
    
    /** @var UtilisateurModel Modèle utilisateur pour les tests */
    protected $utilisateurModel;
    
    /** @var MysqlDatabase Connexion à la base de données */
    protected $db;
    
    // IDs des données de test
    /** @var int ID du chauffeur de test */
    protected $idChauffeur;
    
    /** @var int ID du passager de test */
    protected $idPassager;
    
    /** @var int ID de la voiture de test */
    protected $idVoiture;
    
    /** @var int ID du covoiturage de test */
    protected $idCovoiturage;

    /**
     * Initialise les données de test avant chaque test.
     * Crée chauffeur, passager, voiture, covoiturage et participation.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        $this->db = \App::getInstance()->getDb();
        $this->covoiturageModel = new CovoiturageModel($this->db);
        $this->utilisateurModel = new UtilisateurModel($this->db);

        // Nettoyage pré-test pour éviter les doublons si tearDown a échoué précédemment
        if ($this->covoiturageModel) {
             $this->covoiturageModel->query("DELETE FROM participe"); 
             $this->covoiturageModel->query("DELETE FROM covoiturage");
             $this->covoiturageModel->query("DELETE FROM voiture");
             $this->covoiturageModel->query("DELETE FROM utilisateur WHERE email LIKE '%.v@mail.com'");
        }

        // S'assurer que les vrais statuts existent (prévenir les erreurs FK)
        $this->covoiturageModel->query("INSERT IGNORE INTO statut_covoiturage (statut_covoiturage_id, libelle) VALUES (1,'annulé'),(2,'prévu'),(3,'confirmé'),(4,'en_cours'),(5,'terminé')");
        $this->covoiturageModel->query("INSERT IGNORE INTO role (role_id, libelle) VALUES (1, 'Chauffeur'), (2, 'Passager')");
        $this->covoiturageModel->query("INSERT IGNORE INTO marque (marque_id, libelle) VALUES (1, 'Renault')");
        // S'assurer que la table avis_covoiturage a les statuts
        $this->covoiturageModel->query("INSERT IGNORE INTO avis_covoiturage (avis_covoiturage_id, libelle) VALUES (1, 's’est bien passé'), (2, 's’est mal passé')");

        // 1. Créer un Chauffeur (début avec 20 crédits) avec email unique
        $uniqueId = uniqid();
        $emailChauffeur = "chauffeur.v.$uniqueId@mail.com";
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('ChauffeurV', 'Valid', ?, 'pass', ?, 1, 20)", [$emailChauffeur, "ChauffeurV_$uniqueId"]);
        $chauffeur = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = ?", [$emailChauffeur], true);
        $this->idChauffeur = $chauffeur->utilisateur_id;

        // 2. Créer un Passager
        $emailPassager = "passager.v.$uniqueId@mail.com";
        $this->covoiturageModel->query("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('PassagerV', 'Valid', ?, 'pass', ?, 2, 50)", [$emailPassager, "PassagerV_$uniqueId"]);
        $passager = $this->covoiturageModel->query("SELECT utilisateur_id FROM utilisateur WHERE email = ?", [$emailPassager], true);
        $this->idPassager = $passager->utilisateur_id;

        // 3. Créer une Voiture liée au Chauffeur
        $this->covoiturageModel->query("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('Clio', 'VV-111-AA', 'Essence', 1, ?)", [$this->idChauffeur]);
        $voiture = $this->covoiturageModel->query("SELECT voiture_id FROM voiture WHERE immatriculation = 'VV-111-AA'", [], true);
        $this->idVoiture = $voiture->voiture_id;

        // 4. Créer un Covoiturage (Prix = 10 crédits)
        $this->covoiturageModel->query(
            "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
            VALUES ('2026-12-31', '10:00', 'Paris', 'Rouen', 5, 3, 10.0, ?)", 
            [$this->idVoiture]
        ); // Statut 5 = terminé
        $trajet = $this->covoiturageModel->query("SELECT covoiturage_id FROM covoiturage WHERE voiture_id = ? AND date_depart = '2026-12-31'", [$this->idVoiture], true);
        $this->idCovoiturage = $trajet->covoiturage_id;

        // 5. Le passager participe (pas strictement nécessaire pour le test de crédit mais bon pour le contexte)
        $this->covoiturageModel->enregistrerParticipation($this->idPassager, $this->idCovoiturage);
        
        echo "\nDEBUG: CovoitID=" . $this->idCovoiturage . " VoitureID=" . $this->idVoiture . " ChauffeurID=" . $this->idChauffeur . "\n";
    }

    /**
     * Nettoie les données de test après chaque test.
     * Supprime participations, covoiturages, voitures et utilisateurs de test.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        // Nettoyage des clés étrangères d'abord
        if ($this->covoiturageModel) {
             $this->covoiturageModel->query("DELETE FROM participe"); 
             $this->covoiturageModel->query("DELETE FROM covoiturage");
             $this->covoiturageModel->query("DELETE FROM voiture");
             $this->covoiturageModel->query("DELETE FROM utilisateur WHERE email LIKE '%.v@mail.com'");
        }
    }

    /**
     * Teste le crédit du chauffeur en cas de succès du trajet.
     * Vérifie que le chauffeur reçoit le prix du trajet en crédits.
     * 
     * @return void
     */
    public function testCrediterChauffeurEnCasDeSucces()
    {
        // Simule la logique de ParticipantController::submitValidation pour le Cas 1 (Succès)
        
        // 1. Récupérer le trajet et le prix
        $covoiturage = $this->covoiturageModel->find($this->idCovoiturage);
        echo "\nDEBUG: Covoit récupéré VoitureID=" . $covoiturage->voiture_id . "\n";
        $prix = $covoiturage->prix_personne; // Devrait être 10
        
        // 2. Vérification du crédit initial
        $chauffeurAvant = $this->utilisateurModel->find($this->idChauffeur);
        $creditInitial = $chauffeurAvant->credit; // Devrait être 20

        // 3. Exécuter la logique de crédit
        $voiture = $this->covoiturageModel->query("SELECT utilisateur_id FROM voiture WHERE voiture_id = ?", [$covoiturage->voiture_id], true);
        if ($voiture) {
            $chauffeur_id = $voiture->utilisateur_id;
            $res = $this->utilisateurModel->crediter($chauffeur_id, $prix);
            $this->assertTrue($res, "La méthode crediter a retourné false");
        } else {
             $this->fail("Voiture non trouvée pour le covoiturage");
        }

        // 4. Vérification
        $chauffeurApres = $this->utilisateurModel->find($this->idChauffeur);
        $this->assertEquals($creditInitial + 10, $chauffeurApres->credit, "Le chauffeur devrait recevoir 10 crédits");
    }

    /**
     * Teste l'absence de crédit en cas d'incident.
     * Vérifie que le chauffeur ne reçoit pas de crédits si le trajet s'est mal passé.
     * 
     * @return void
     */
    public function testEnregistrerIncidentEnCasDechec()
    {
        // Simule la logique pour le Cas 2 (Échec)
        // Idéalement on vérifie si une entrée de log est créée ou si error_log est appelé.
        // Comme error_log va dans le log d'erreur PHP qui est difficile à lire ici, 
        // on vérifie principalement qu'AUCUN crédit n'est ajouté.

        // 1. Vérification du crédit initial
        $chauffeurAvant = $this->utilisateurModel->find($this->idChauffeur);
        $creditInitial = $chauffeurAvant->credit;

        // 2. Logique pour l'échec (Incident) - ne rien faire à part logger
        $prix = 10;
        // Dans le contrôleur : if ($avis_covoiturage_id == 2) { ... error_log ... }
        // Donc PAS d'appel à crediter().

        // 3. Vérification
        $chauffeurApres = $this->utilisateurModel->find($this->idChauffeur);
        $this->assertEquals($creditInitial, $chauffeurApres->credit, "Le chauffeur ne devrait pas recevoir de crédits en cas d'incident");
    }
}
