<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\UtilisateurModel;

require_once __DIR__ . '/../../app/App.php'; 

/**
 * Tests unitaires pour la User Story 10 (Historique des covoiturages).
 * Vérifie l'historique chauffeur, l'historique passager et l'annulation de trajets.
 */
class US10_HistoriqueTest extends TestCase
{
    /** @var UtilisateurModel Modèle utilisateur pour les tests */
    protected $modele;
    
    /** @var MysqlDatabase Connexion à la base de données */
    protected $db;
    
    /** @var array IDs des données de test à supprimer */
    protected $idsToDelete = [];

    /**
     * Initialise le modèle utilisateur avant chaque test.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Initialiser le modèle
        $this->db = \App::getInstance()->getDb();
        $this->modele = new UtilisateurModel($this->db);
    }

    /**
     * Nettoie les données de test après chaque test.
     * Supprime dans l'ordre correct pour éviter les violations de FK.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        // Nettoyer les données dans le bon ordre pour éviter les violations FK
        // 1. Nettoyer les participations liées aux trajets créés
        if (!empty($this->idsToDelete['covoiturage'])) {
             $tripIds = $this->idsToDelete['covoiturage'];
             $placeholders = implode(',', array_fill(0, count($tripIds), '?'));
             $this->db->prepare("DELETE FROM participe WHERE covoiturage_id IN ($placeholders)", $tripIds);
        }

        $tables = ['participe', 'covoiturage', 'voiture', 'utilisateur'];
        foreach ($tables as $table) {
            if (!empty($this->idsToDelete[$table])) {
                $ids = $this->idsToDelete[$table];
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $clause = $table === 'participe' ? 'covoiturage_id' : $this->getIdColumn($table); 
                // Note : la suppression de participe par covoiturage_id peut être imprécise si plusieurs utilisateurs participent
                // En fait pour participe on devrait probablement supprimer par utilisateur_id aussi.
                // Mais essayons avec la colonne ID stricte.
                if ($table === 'participe') {
                     // Cas spécial : on n'a pas suivi les IDs de participe correctement ?
                     // Mon helper createParticipation N'ajoute PAS à idsToDelete car la suppression explicite est difficile.
                     // Mais la violation d'intégrité implique qu'ils SONT présents.
                     // Je dois ajouter `delete from participe where covoiturage_id IN (...)`
                     // Mon helper createParticipation NE push PAS vers idsToDelete.
                     // Ah, je vois : createParticipation N'ajoute PAS à idsToDelete.
                     // Mais createTrip AJOUTE à idsToDelete['covoiturage'].
                     // Quand j'essaie de supprimer covoiturage, ça échoue car référencé dans participe.
                     // Donc je dois supprimer FROM participe WHERE covoiturage_id IN (ids des trajets supprimés).
                     
                     if (!empty($this->idsToDelete['covoiturage'])) {
                         $tripIds = $this->idsToDelete['covoiturage'];
                         $placeholders = implode(',', array_fill(0, count($tripIds), '?'));
                         $this->db->prepare("DELETE FROM participe WHERE covoiturage_id IN ($placeholders)", $tripIds);
                     }
                } else {
                     $this->db->prepare("DELETE FROM $table WHERE {$this->getIdColumn($table)} IN ($placeholders)", $ids);
                }
            }
        }
    }

    /**
     * Retourne le nom de la colonne ID pour une table donnée.
     * 
     * @param string $table Nom de la table
     * @return string Nom de la colonne ID
     */
    protected function getIdColumn($table) {
        $map = [
            'utilisateur' => 'utilisateur_id',
            'covoiturage' => 'covoiturage_id',
            'voiture' => 'voiture_id',
            'participe' => 'covoiturage_id' // Attention à la logique de suppression pour la table de jointure
        ];
        return $map[$table] ?? 'id';
    }
    
    /**
     * Crée un utilisateur de test avec le rôle spécifié.
     * 
     * @param int $roleId ID du rôle (1=Chauffeur, 2=Passager, 3=Chauffeur-Passager)
     * @return int ID de l'utilisateur créé
     */
    protected function createUser($roleId) {
        $pseudo = 'TestUser_' . uniqid();
        $email = $pseudo . '@test.com';
        $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('Test', 'User', ?, 'pass', ?, ?, 20)", [$email, $pseudo, $roleId]);
        $user = $this->modele->findByEmail($email);
        $this->idsToDelete['utilisateur'][] = $user->utilisateur_id;
        return $user->utilisateur_id;
    }

    /**
     * Crée une voiture de test pour un utilisateur.
     * 
     * @param int $userId ID du propriétaire
     * @return int ID de la voiture créée
     */
    protected function createCar($userId) {
        $immat = 'XX-' . uniqid() . '-ZZ';
        $this->db->prepare("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('TestCar', ?, 'Essence', 1, ?)", [$immat, $userId]);
        $carId = $this->db->getLastInsertId();
        $this->idsToDelete['voiture'][] = $carId;
        return $carId;
    }

    /**
     * Crée un trajet de test avec la voiture et la date spécifiées.
     * 
     * @param int $carId ID de la voiture
     * @param string $date Date de départ au format YYYY-MM-DD
     * @param int $statut ID du statut (par défaut 2 = prévu)
     * @return int ID du trajet créé
     */
    protected function createTrip($carId, $date, $statut = 2) {
       $this->db->prepare("INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
       VALUES (?, '10:00:00', 'Paris', ?, '12:00:00', 'Lyon', ?, 3, 10, ?)", 
       [$date, $date, $statut, $carId]);
       $tripId = $this->db->getLastInsertId();
       $this->idsToDelete['covoiturage'][] = $tripId;
       return $tripId;
    }

    /**
     * Crée une participation d'un utilisateur à un trajet.
     * 
     * @param int $userId ID de l'utilisateur participant
     * @param int $tripId ID du trajet
     * @return void
     */
    protected function createParticipation($userId, $tripId) {
        $this->db->prepare("INSERT INTO participe (utilisateur_id, covoiturage_id) VALUES (?, ?)", [$userId, $tripId]);
        // Pas d'ID à suivre pour la suppression, habituellement supprimé en cascade ou manuellement en supprimant utilisateur/trajet. Mais par sécurité :
        // On ne peut pas supprimer facilement par ID.
    }

    /**
     * Teste que l'historique chauffeur retourne les trajets passés.
     * 
     * @return void
     */
    public function testHistoriqueChauffeur_AvecTrajetPasse_RetourneTrajet()
    {
        // 1. Créer un Chauffeur (Rôle 1)
        $chauffeurId = $this->createUser(1); // Chauffeur
        $voitureId = $this->createCar($chauffeurId);

        // 2. Créer un trajet passé (Hier)
        $hier = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($voitureId, $hier);

        // 3. Récupérer l'historique
        $historique = $this->modele->getHistoriqueCovoiturages($chauffeurId);

        // 4. Vérification
        $this->assertNotEmpty($historique);
        $this->assertEquals($hier, $historique[0]->date_depart);
    }

    /**
     * Teste que l'historique chauffeur ne retourne pas les trajets futurs.
     * 
     * @return void
     */
    public function testHistoriqueChauffeur_AvecTrajetFutur_RetourneVide()
    {
        // 1. Créer un Chauffeur (Rôle 1)
        $chauffeurId = $this->createUser(1);
        $voitureId = $this->createCar($chauffeurId);

        // 2. Créer un trajet futur (Demain)
        $demain = date('Y-m-d', strtotime('+1 day'));
        $this->createTrip($voitureId, $demain);

        // 3. Récupérer l'historique
        $historique = $this->modele->getHistoriqueCovoiturages($chauffeurId);

        // 4. Vérification
        $this->assertEmpty($historique);
    }

    /**
     * Teste que l'historique chauffeur est vide pour un passager.
     * 
     * @return void
     */
    public function testHistoriqueChauffeur_EnTantQuePassager_RetourneVide()
    {
        // 1. Créer un Passager (Rôle 2)
        $passagerId = $this->createUser(2);
        
        // Même s'il possède une voiture et un trajet (ne devrait pas arriver dans la logique de l'app mais la DB le permet via nos helpers de test)
        // Forçons-le pour tester la logique de vérification de rôle.
        // On a besoin d'une voiture liée à lui.
        $voitureId = $this->createCar($passagerId);
        $hier = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($voitureId, $hier);

        // 3. Récupérer l'historique en appelant getHistoriqueCovoiturages (qui est pour les Chauffeurs)
        $historique = $this->modele->getHistoriqueCovoiturages($passagerId);

        // 4. Vérifier que c'est vide car le rôle est Passager (2)
        $this->assertEmpty($historique);
    }

    /**
     * Teste que l'historique passager retourne les participations passées.
     * 
     * @return void
     */
    public function testHistoriquePassager_AvecParticipationPassee_RetourneTrajet()
    {
        // 1. Créer un Passager (Rôle 2)
        $passagerId = $this->createUser(2);

        // 2. Créer un trajet (par un autre chauffeur)
        $chauffeurId = $this->createUser(1);
        $voitureId = $this->createCar($chauffeurId);
        $hier = date('Y-m-d', strtotime('-1 day'));
        $trajetId = $this->createTrip($voitureId, $hier);

        // 3. Participer
        $this->createParticipation($passagerId, $trajetId);

        // 4. Récupérer l'historique
        $historique = $this->modele->getHistoriqueParticipations($passagerId);

        // 5. Vérification
        $this->assertNotEmpty($historique);
        $this->assertEquals($trajetId, $historique[0]->covoiturage_id);
    }
    
    /**
     * Teste que l'historique passager est vide pour un chauffeur pur.
     * 
     * @return void
     */
    public function testHistoriquePassager_EnTantQueChauffeur_RetourneVide()
    {
        // 1. Créer un Chauffeur (Rôle 1)
        // Consigne US : "considérer son statut Passager". Chauffeur seul (1) N'est PAS un passager (2) ou (3).
        $chauffeurId = $this->createUser(1); 
        
        // 2. Créer un trajet et participer ?
        // La logique dit vérification de rôle spécifique.
        // Un chauffeur *pourrait* participer à un autre trajet techniquement si l'app le permet.
        // Mais notre méthode getHistoriqueParticipations vérifie si le rôle est IN ['Passager', 'Chauffeur-Passager'].
        
        // Faisons-le participer
        $autreChauffeurId = $this->createUser(1);
        $voitureId = $this->createCar($autreChauffeurId);
        $hier = date('Y-m-d', strtotime('-1 day'));
        $trajetId = $this->createTrip($voitureId, $hier);
        $this->createParticipation($chauffeurId, $trajetId);

        // 3. Récupérer l'historique
        $historique = $this->modele->getHistoriqueParticipations($chauffeurId);

        // 4. Vérifier que c'est vide car le rôle 1 n'est pas autorisé dans getHistoriqueParticipations
        $this->assertEmpty($historique);
    }
    
    /**
     * Teste qu'un Chauffeur-Passager a accès aux deux historiques.
     * 
     * @return void
     */
    public function testHistoriqueChauffeurPassager_AccesAuxDeux()
    {
        // 1. Créer un Chauffeur-Passager (Rôle 3)
        $cpId = $this->createUser(3);
        
        // 2. En tant que Chauffeur (Trajet passé)
        $voitureId = $this->createCar($cpId);
        $hier = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($voitureId, $hier);
        
        // 3. En tant que Passager (Participation passée)
        $autreChauffeurId = $this->createUser(1);
        $autreVoitureId = $this->createCar($autreChauffeurId);
        $autreTrajetId = $this->createTrip($autreVoitureId, $hier);
        $this->createParticipation($cpId, $autreTrajetId);
        
        // 4. Vérifier l'accès
        $historiqueChauffeur = $this->modele->getHistoriqueCovoiturages($cpId);
        $historiquePassager = $this->modele->getHistoriqueParticipations($cpId);
        
        $this->assertNotEmpty($historiqueChauffeur);
        $this->assertNotEmpty($historiquePassager);
    }

    /**
     * Teste que l'annulation de trajet rembourse les crédits et met à jour le statut.
     * 
     * @return void
     */
    public function testAnnulerTrajet_RembourseCreditsEtMajStatut()
    {
        // 1. Créer un Chauffeur avec 20 crédits
        $chauffeurId = $this->createUser(1);
        $voitureId = $this->createCar($chauffeurId);
        
        // 2. Créer un trajet (Statut 2 = Prévu)
        $demain = date('Y-m-d', strtotime('+1 day'));
        $trajetId = $this->createTrip($voitureId, $demain);
        
        // 3. Créer un Passager et une Participation (pour le test d'email)
        $passagerId = $this->createUser(2);
        $this->createParticipation($passagerId, $trajetId);
        
        // Vider le fichier de log avant le test
        $fichierLog = __DIR__ . '/../../log/email_debug.txt';
        file_put_contents($fichierLog, '');

        // 4. Exécuter l'annulation via le modèle
        $covoiturageModel = new \NsAppEcoride\Model\CovoiturageModel($this->db);
        $resultat = $covoiturageModel->cancelTrip($trajetId, $chauffeurId);
        
        // 5. Vérifier les mises à jour en DB
        $trajet = $this->db->prepare("SELECT * FROM covoiturage WHERE covoiturage_id = ?", [$trajetId], null, true);
        $utilisateur = $this->db->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?", [$chauffeurId], null, true);
        
        $this->assertEquals(1, $trajet->statut_covoiturage_id);
        $this->assertEquals(22, $utilisateur->credit); // 20 départ + 2 remboursement

        // 6. Vérifier les données retournées (Info de notification)
        $this->assertIsArray($resultat);
        $this->assertArrayHasKey('participants', $resultat);
        $this->assertNotEmpty($resultat['participants']);
        // Vérifier qu'on a le bon participant par email (puisque user_id n'est pas sélectionné)
        $this->assertStringContainsString('TestUser_', $resultat['participants'][0]->email);
        // Note : getParticipants retourne des objets avec email/pseudo, vérifier le count
        $this->assertCount(1, $resultat['participants']);
    }
}
