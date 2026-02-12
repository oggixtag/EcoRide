<?php

use PHPUnit\Framework\TestCase;
use NsCoreEcoride\Auth\DbAuth;
use NsCoreEcoride\Database\MysqlDatabase;

/**
 * Tests unitaires pour l'indépendance des sessions Admin/Employé.
 * Vérifie que les sessions admin et employé sont isolées et peuvent coexister.
 */
class US12_US13_SessionTest extends TestCase
{
    /** @var MysqlDatabase Connexion à la base de données */
    private $db;
    
    /** @var DbAuth Service d'authentification */
    private $auth;

    /**
     * Initialise la connexion DB et l'authentification avant chaque test.
     * Nettoie les sessions existantes.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Connexion directe à la base de données pour les tests via App
        $this->db = \App::getInstance()->getDb();
        $this->auth = new DbAuth($this->db);
        
        // Initialiser la session pour les tests
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Nettoyer les sessions avant chaque test
        unset($_SESSION['auth_admin']);
        unset($_SESSION['auth_employe']);

        // Insérer Poste
        $this->db->query("INSERT IGNORE INTO poste (id_poste, intitule) VALUES (1, 'Administrateur'), (2, 'Employé')");

        // Insérer Département (pour FK)
        $this->db->query("INSERT IGNORE INTO departement (id_dept, nom_dept) VALUES (1, 'IT')");

        // Insérer Admin
        $this->db->prepare("INSERT INTO employe (nom, prenom, email, password, pseudo, id_poste, id_dept) VALUES ('AdminTest', 'Admin', 'admin.test@mail.com', 'pwd_admin_test', 'admintest', 1, 1)", []);
        $this->adminId = $this->db->getLastInsertId();

        // Insérer Employé
        $this->db->prepare("INSERT INTO employe (nom, prenom, email, password, pseudo, id_poste, id_dept) VALUES ('EmpTest', 'Emp', 'emp.test@mail.com', 'pwd_emp_test', 'emptest', 2, 1)", []);
        $this->empId = $this->db->getLastInsertId();
    }

    /**
     * Nettoie les sessions après chaque test.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        if (isset($this->adminId)) $this->db->prepare("DELETE FROM employe WHERE id_emp = ?", [$this->adminId]);
        if (isset($this->empId)) $this->db->prepare("DELETE FROM employe WHERE id_emp = ?", [$this->empId]);

        // Nettoyer les sessions après chaque test
        unset($_SESSION['auth_admin']);
        unset($_SESSION['auth_employe']);
    }

    /**
     * TEST 1: Connexion admin crée auth_admin avec l'ID
     */
    public function testConnexionAdminCreeSessionAdmin()
    {
        // Connexion avec les identifiants admin
        $result = $this->auth->loginEmploye('admintest', 'pwd_admin_test');
        
        // loginEmploye() retourne l'id_poste (1=admin) ou false
        $this->assertNotFalse($result, "La connexion admin doit réussir");
        $this->assertEquals(1, $result, "id_poste doit être 1 pour admin");
        $this->assertArrayHasKey('auth_admin', $_SESSION, "La clé auth_admin doit exister");
        $this->assertNotEmpty($_SESSION['auth_admin'], "auth_admin doit contenir l'ID admin");
        $this->assertArrayNotHasKey('auth_employe', $_SESSION, "auth_employe ne doit PAS être défini pour un admin");
        
        // Vérifier les méthodes helper
        $this->assertTrue($this->auth->isAdmin(), "isAdmin() doit retourner true");
        $this->assertFalse($this->auth->isEmploye(), "isEmploye() doit retourner false pour un admin");
    }

    /**
     * TEST 2: Connexion employé crée auth_employe avec l'ID
     */
    public function testConnexionEmployeCreeSessionEmploye()
    {
        // Connexion avec les identifiants employé
        $result = $this->auth->loginEmploye('emptest', 'pwd_emp_test');
        
        // loginEmploye() retourne l'id_poste (2=employe) ou false
        $this->assertNotFalse($result, "La connexion employé doit réussir");
        $this->assertEquals(2, $result, "id_poste doit être 2 pour employé");
        $this->assertArrayHasKey('auth_employe', $_SESSION, "La clé auth_employe doit exister");
        $this->assertNotEmpty($_SESSION['auth_employe'], "auth_employe doit contenir l'ID employé");
        $this->assertArrayNotHasKey('auth_admin', $_SESSION, "auth_admin ne doit PAS être défini pour un employé");
        
        // Vérifier les méthodes helper
        $this->assertTrue($this->auth->isEmploye(), "isEmploye() doit retourner true");
        $this->assertFalse($this->auth->isAdmin(), "isAdmin() doit retourner false pour un employé");
    }

    /**
     * TEST 3: Isolation des sessions - Les deux peuvent coexister
     */
    public function testIsolationSession()
    {
        // Simuler admin et employé connectés simultanément
        $_SESSION['auth_admin'] = 1;  // ID de l'admin ndrndr
        $_SESSION['auth_employe'] = 2;  // ID de l'employé sophiedurand
        
        $this->assertTrue($this->auth->isAdmin(), "Admin doit être connecté");
        $this->assertTrue($this->auth->isEmploye(), "Employé doit être connecté");
        
        // Simuler déconnexion employé (ne doit pas affecter admin)
        unset($_SESSION['auth_employe']);
        
        $this->assertTrue($this->auth->isAdmin(), "Admin doit TOUJOURS être connecté après logout employé");
        $this->assertFalse($this->auth->isEmploye(), "Employé doit être déconnecté");
        
        // Reconnexion employé + déconnexion admin
        $_SESSION['auth_employe'] = 2;
        unset($_SESSION['auth_admin']);
        
        $this->assertFalse($this->auth->isAdmin(), "Admin doit être déconnecté");
        $this->assertTrue($this->auth->isEmploye(), "Employé doit TOUJOURS être connecté après logout admin");
    }

    /**
     * TEST 4: getAdminId() et getEmployeId() retournent les bonnes valeurs
     */
    public function testMethodesGetId()
    {
        $_SESSION['auth_admin'] = 1;
        $_SESSION['auth_employe'] = 2;
        
        $this->assertEquals(1, $this->auth->getAdminId(), "getAdminId() doit retourner 1");
        $this->assertEquals(2, $this->auth->getEmployeId(), "getEmployeId() doit retourner 2");
        
        unset($_SESSION['auth_admin']);
        unset($_SESSION['auth_employe']);
        
        $this->assertNull($this->auth->getAdminId(), "getAdminId() doit retourner null si non connecté");
        $this->assertNull($this->auth->getEmployeId(), "getEmployeId() doit retourner null si non connecté");
    }
}
