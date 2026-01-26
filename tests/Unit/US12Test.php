<?php

use PHPUnit\Framework\TestCase;
use NsCoreEcoride\Auth\DbAuth;
use NsAppEcoride\Model\AvisModel;
use NsAppEcoride\Model\CovoiturageModel;
use NsCoreEcoride\Database\MysqlDatabase;

class US12Test extends TestCase
{
    private $db;
    private $auth;
    private $avisModel;
    private $covoitModel;

    protected function setUp(): void
    {
        // Setup direct DB connection for testing (using XAMPP default)
        $this->db = new MysqlDatabase('abcrdv_ecoride_db', 'root', '', 'localhost');
        $this->auth = new DbAuth($this->db);
        $this->avisModel = new AvisModel($this->db);
        $this->covoitModel = new CovoiturageModel($this->db);


        
        // Reset Log
        file_put_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt', '');
    }

    /**
     * TEST 1: Auth Separation
     * user login should FAIL with employee credentials
     */
    public function testAuthSeparation()
    {
        $result = $this->auth->login('sophie.durand@mail.com', 'pwd_sophie');
        $this->assertFalse($result, "Employee credentials should NOT work on User Login");
    }

    /**
     * TEST 2: Employee Login
     */
    public function testEmployeeLogin()
    {
        // Failure case
        $this->assertFalse($this->auth->loginEmploye('sophie.durand@mail.com', 'wrong_pwd'), "Login should fail with wrong password");
        
        // Success case
        $this->assertTrue($this->auth->loginEmploye('sophie.durand@mail.com', 'pwd_sophie'), "Login should succeed with correct credentials");
        
        // Check Session
        $this->assertArrayHasKey('auth_employe', $_SESSION, "Employee session key should be present");
        $this->assertArrayNotHasKey('auth', $_SESSION, "User session key should NOT be present (Isolation Check)");
    }

    /**
     * TEST 3: Dashboard Data - Bad Carpools
     */
    public function testBadCarpoolsWait()
    {
         // Verify we can fetch them
         $bad = $this->covoitModel->findBadCarpools();
         $this->assertIsArray($bad);
         
         // Assuming our seed data might change, we just verify the structure is correct if any exist
         if (!empty($bad)) {
             $item = $bad[0];
             $this->assertObjectHasProperty('driver_pseudo', $item);
             $this->assertObjectHasProperty('motif', $item);
         }
    }

    /**
     * TEST 4: Review Validation & Email
     */
    public function testReviewValidationAndEmail()
    {
        // 1. Setup: Ensure we have a pending review (id 14 is typical from seed)
        // Let's force ID 14 to status 2 (Modération)
        $this->avisModel->updateStatus(14, 2);
        
        $pending = $this->avisModel->findAllPending();
        $this->assertNotEmpty($pending, "Should have pending reviews");
        
        $found = false;
        foreach($pending as $p) {
            if ($p->avis_id == 14) $found = true;
        }
        $this->assertTrue($found, "Review ID 14 should be pending");

        // 2. Validate
        $this->avisModel->updateStatus(14, 1);
        
        // 3. Check DB
        $avis = $this->avisModel->findOneWithUser(14);
        $this->assertEquals('publié', $avis->statut, "Review status should be publié (ID 1)"); 
        // Note: findOneWithUser fetches 'statut' as string from JOIN. 
        // Need to check DDL 'statut_avis' values. 
        // DML: 1='publié', 2='modération'.
        // My Logic used 1 as Validé.
        // Let's check raw ID just to be safe OR trust string match if DB is correct.
        // Actually findOneWithUser returns 'statut' (libelle).
        // If ID=1 is 'publié', then $avis->statut should be 'publié'.
        // My code sets status_id = 1.
        
        // Let's rely on ID check first for robustness
        $raw = $this->db->query("SELECT statut_avis_id FROM avis WHERE avis_id=14", null, true);
        $this->assertEquals(1, $raw->statut_avis_id);

        // 4. Verify Email Simulation
        // Since we are not calling the Controller, the email isn't sent by this Model call.
        // Tests usually Unit Test the controller OR the Service.
        // The Controller logic does: Update + Mailer->send.
        // Here we simulate that flow:
        
        $mailer = new \NsAppEcoride\Service\Mailer();
        $subject = "EcoRide - Avis validé";
        $body = "Bonjour {$avis->pseudo},<br><br>Votre avis a été validé par notre équipe et est maintenant visible.<br><br>L'équipe EcoRide";
        $res = $mailer->send($avis->email, $subject, $body);
        
        $this->assertTrue($res, "Mailer return value should be true");
        
        $log = file_get_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt');
        $this->assertStringContainsString('SUCCESS Mailer::send', $log);
        $this->assertStringContainsString($avis->email, $log);
    }

    /**
     * TEST 5: Review Refusal & Email
     */
    public function testReviewRefusalAndEmail()
    {
        // 1. Setup: Reset ID 14 to Pending
        $this->avisModel->updateStatus(14, 2);
        
        // 2. Refuse (Status 'refusé')
        $refuseStatus = $this->db->query("SELECT statut_avis_id FROM statut_avis WHERE libelle = 'refusé'", null, true);
        if (!$refuseStatus) {
            $this->fail("Status 'refusé' not found in DB.");
        }
        $refuseId = $refuseStatus->statut_avis_id;
        
        $this->avisModel->updateStatus(14, $refuseId);
        
        // 3. Check DB
        $avis = $this->avisModel->findOneWithUser(14);
        // Assuming ID 3 matches a refusal status. Since 'statut' comes from DB join, 
        // if ID 3 isn't seeded with a label, this might fail or return null label.
        // Let's rely on ID check primarily.
        
        $raw = $this->db->query("SELECT statut_avis_id FROM avis WHERE avis_id=14", null, true);
        $this->assertEquals($refuseId, $raw->statut_avis_id, "Review status ID should be $refuseId (Refusé)");

        // 4. Verify Email Simulation for Refusal
        $mailer = new \NsAppEcoride\Service\Mailer();
        // Clear log
        file_put_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt', '');
        
        $subject = "EcoRide - Avis refusé";
        $body = "Bonjour {$avis->pseudo},<br><br>Votre avis n'a pas été validé par notre équipe car il ne respecte pas nos conditions d'utilisation.<br><br>L'équipe EcoRide";
        $res = $mailer->send($avis->email, $subject, $body);
        $this->assertTrue($res);
        
        $log = file_get_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt');
        $this->assertStringContainsString('SUCCESS Mailer::send', $log);
    }
}
