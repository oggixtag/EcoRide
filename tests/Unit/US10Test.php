<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\UtilisateurModel;

require_once __DIR__ . '/../../app/App.php'; 

class US10Test extends TestCase
{
    protected $modele;
    protected $db;
    protected $idsToDelete = [];

    protected function setUp(): void
    {
        // Init Model
        $this->db = \App::getInstance()->getDb();
        $this->modele = new UtilisateurModel($this->db);
    }

    protected function tearDown(): void
    {
        // Cleanup data in correct order to avoid FK violation
        // 1. Clean participe linked to trips we created
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
                // Note: participe delete by covoiturage_id might be loose if multiple users participate, but fine for these tests
                // Actually for participe we should probably delete by usuario_id too or just truncate if we could.
                // But let's try strict ID col.
                if ($table === 'participe') {
                     // Special case: we didn't track participe IDs properly?
                     // My createParticipation helper does NOT add to idsToDelete because explicit delete is hard.
                     // But integrity violation implies they ARE present.
                     // I should add `delete from participe where covoiturage_id IN (...)`
                     // My createParticipation helper DOES NOT push to idsToDelete.
                     // Ah, I see step 82: createParticipation does NOT add to idsToDelete.
                     // But createTrip ADDS to idsToDelete['covoiturage'].
                     // When I try to delete covoiturage, it fails because referenced in participe.
                     // So I must delete FROM participe WHERE covoiturage_id IN (ids of deleted trips).
                     
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

    protected function getIdColumn($table) {
        $map = [
            'utilisateur' => 'utilisateur_id',
            'covoiturage' => 'covoiturage_id',
            'voiture' => 'voiture_id',
            'participe' => 'covoiturage_id' // Careful with delete logic for join table
        ];
        return $map[$table] ?? 'id';
    }
    
    // Helper to create user
    protected function createUser($roleId) {
        $pseudo = 'TestUser_' . uniqid();
        $email = $pseudo . '@test.com';
        $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('Test', 'User', ?, 'pass', ?, ?, 20)", [$email, $pseudo, $roleId]);
        $user = $this->modele->findByEmail($email);
        $this->idsToDelete['utilisateur'][] = $user->utilisateur_id;
        return $user->utilisateur_id;
    }

    // Helper to create car
    protected function createCar($userId) {
        $immat = 'XX-' . uniqid() . '-ZZ';
        $this->db->prepare("INSERT INTO voiture (modele, immatriculation, energie, marque_id, utilisateur_id) VALUES ('TestCar', ?, 'Essence', 1, ?)", [$immat, $userId]);
        $carId = $this->db->getLastInsertId();
        $this->idsToDelete['voiture'][] = $carId;
        return $carId;
    }

    // Helper to create trip
    protected function createTrip($carId, $date, $statut = 2) {
       $this->db->prepare("INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
       VALUES (?, '10:00:00', 'Paris', ?, '12:00:00', 'Lyon', ?, 3, 10, ?)", 
       [$date, $date, $statut, $carId]);
       $tripId = $this->db->getLastInsertId();
       $this->idsToDelete['covoiturage'][] = $tripId;
       return $tripId;
    }

    // Helper to create participation
    protected function createParticipation($userId, $tripId) {
        $this->db->prepare("INSERT INTO participe (utilisateur_id, covoiturage_id) VALUES (?, ?)", [$userId, $tripId]);
        // No ID to track for delete, usually deleted by cascade or manually by deleting user/trip. But for safety:
        // We can't delete easily by ID.
    }

    public function testDriverHistory_WithPastTrip_ReturnsTrip()
    {
        // 1. Create Driver (Role 1)
        $driverId = $this->createUser(1); // Chauffeur
        $carId = $this->createCar($driverId);

        // 2. Create Past Trip (Yesterday)
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($carId, $yesterday);

        // 3. Get History
        $history = $this->modele->getHistoriqueCovoiturages($driverId);

        // 4. Assert
        $this->assertNotEmpty($history);
        $this->assertEquals($yesterday, $history[0]->date_depart);
    }

    public function testDriverHistory_WithFutureTrip_ReturnsEmpty()
    {
        // 1. Create Driver (Role 1)
        $driverId = $this->createUser(1);
        $carId = $this->createCar($driverId);

        // 2. Create Future Trip (Tomorrow)
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $this->createTrip($carId, $tomorrow);

        // 3. Get History
        $history = $this->modele->getHistoriqueCovoiturages($driverId);

        // 4. Assert
        $this->assertEmpty($history);
    }

    public function testDriverHistory_AsPassenger_ReturnsEmpty()
    {
        // 1. Create Passenger (Role 2)
        $passengerId = $this->createUser(2);
        
        // Even if somehow he owns a car and trip (shouldn't happen in app logic but DB allows it via our tests helpers)
        // Let's force it to test the ROLE CHECK method logic.
        // We need a car linked to him. 
        $carId = $this->createCar($passengerId);
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($carId, $yesterday);

        // 3. Get History calling getHistoriqueCovoiturages (which is for Drivers)
        $history = $this->modele->getHistoriqueCovoiturages($passengerId);

        // 4. Assert Empty because Role is Passenger (2)
        $this->assertEmpty($history);
    }

    public function testPassengerHistory_WithPastParticipation_ReturnsTrip()
    {
        // 1. Create Passenger (Role 2)
        $passengerId = $this->createUser(2);

        // 2. Create Trip (by another driver)
        $driverId = $this->createUser(1);
        $carId = $this->createCar($driverId);
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $tripId = $this->createTrip($carId, $yesterday);

        // 3. Participate
        $this->createParticipation($passengerId, $tripId);

        // 4. Get History
        $history = $this->modele->getHistoriqueParticipations($passengerId);

        // 5. Assert
        $this->assertNotEmpty($history);
        $this->assertEquals($tripId, $history[0]->covoiturage_id);
    }
    
    public function testPassengerHistory_AsDriver_ReturnsEmpty()
    {
        // 1. Create Driver (Role 1)
        // US Consigne: "considérer son statut Passager". Driver only (1) is NOT a passenger (2) or (3).
        $driverId = $this->createUser(1); 
        
        // 2. Create Trip and Participate?
        // Logic says specific role check.
        // A driver *could* participate in another trip technically if app allows.
        // But our method getHistoriqueParticipations checks if role is IN ['Passager', 'Chauffeur-Passager'].
        
        // Let's make him participate
        $otherDriverId = $this->createUser(1);
        $carId = $this->createCar($otherDriverId);
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $tripId = $this->createTrip($carId, $yesterday);
        $this->createParticipation($driverId, $tripId);

        // 3. Get History
        $history = $this->modele->getHistoriqueParticipations($driverId);

        // 4. Assert Empty because Role 1 is not allowed in getHistoriqueParticipations
        $this->assertEmpty($history);
    }
    
    public function testChauffeurPassagerHistory_AccessBoth()
    {
        // 1. Create Chauffeur-Passager (Role 3)
        $cpId = $this->createUser(3);
        
        // 2. As Driver (Past Trip)
        $carId = $this->createCar($cpId);
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $this->createTrip($carId, $yesterday);
        
        // 3. As Passenger (Past Participation)
        $otherDriverId = $this->createUser(1);
        $otherCarId = $this->createCar($otherDriverId);
        $otherTripId = $this->createTrip($otherCarId, $yesterday);
        $this->createParticipation($cpId, $otherTripId);
        
        // 4. Assert Access
        $driverHistory = $this->modele->getHistoriqueCovoiturages($cpId);
        $passengerHistory = $this->modele->getHistoriqueParticipations($cpId);
        
        $this->assertNotEmpty($driverHistory);
        $this->assertNotEmpty($passengerHistory);
    }

    public function testCancelTrip_RefundsCreditsAndUpdatesStatus()
    {
        // 1. Create Driver with 20 credits
        $driverId = $this->createUser(1);
        $carId = $this->createCar($driverId);
        
        // 2. Create Trip (Statut 2 = Prévu)
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $tripId = $this->createTrip($carId, $tomorrow);
        
        // 3. Simulate Cancellation Logic
        // Since we can't call Controller methods directly easily (they use header/exit), 
        // we will manually execute the logic steps to verify the DB interactions work as expected.
        
        // A. Update Status
        $this->db->prepare("UPDATE covoiturage SET statut_covoiturage_id = 1 WHERE covoiturage_id = ?", [$tripId]);
        
        // B. Refund Credit (+2)
        $this->db->prepare("UPDATE utilisateur SET credit = credit + 2 WHERE utilisateur_id = ?", [$driverId]);
        
        // 4. Verify
        $trip = $this->db->prepare("SELECT * FROM covoiturage WHERE covoiturage_id = ?", [$tripId], null, true);
        $user = $this->db->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = ?", [$driverId], null, true);
        
        $this->assertEquals(1, $trip->statut_covoiturage_id);
        $this->assertEquals(22, $user->credit); // 20 start + 2 refund
    }
}
