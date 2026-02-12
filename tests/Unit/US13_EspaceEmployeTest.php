<?php

use PHPUnit\Framework\TestCase;
use NsCoreEcoride\Auth\DbAuth;
use NsAppEcoride\Model\AvisModel;
use NsAppEcoride\Model\CovoiturageModel;
use NsCoreEcoride\Database\MysqlDatabase;

/**
 * Tests unitaires pour la User Story 12 (Espace Employé).
 * Vérifie l'authentification employé, la validation des avis et l'envoi d'emails.
 */
class US13_EspaceEmployeTest extends TestCase
{
    /** @var MysqlDatabase Connexion à la base de données */
    private $db;
    
    /** @var DbAuth Service d'authentification */
    private $auth;
    
    /** @var AvisModel Modèle des avis */
    private $avisModel;
    
    /** @var CovoiturageModel Modèle des covoiturages */
    private $covoitModel;

    /**
     * Initialise les modèles et la connexion DB avant chaque test.
     * Réinitialise le fichier de log des emails.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        // Configuration de la connexion DB directe pour les tests via App
        $this->db = \App::getInstance()->getDb();
        $this->auth = new DbAuth($this->db);
        $this->avisModel = new AvisModel($this->db);
        $this->covoitModel = new CovoiturageModel($this->db);


        
        // Réinitialiser le log
        file_put_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt', '');

        // Créer un employé de test
        // 1. Insérer Poste
        $this->db->query("INSERT IGNORE INTO poste (id_poste, intitule) VALUES (1, 'Administrateur'), (2, 'Employé')");
        
        // 2. Insérer Département (pour FK)
        $this->db->query("INSERT IGNORE INTO departement (id_dept, nom_dept) VALUES (1, 'IT')");

        // 3. Insérer Employé avec id_dept=1
        $this->db->prepare("INSERT INTO employe (nom, prenom, email, password, pseudo, id_poste, id_dept) VALUES ('TestEmploye', 'Sophie', 'test.employe@mail.com', 'pwd_test', 'testemploye', 2, 1)", []);
        $this->employeId = $this->db->getLastInsertId();
    }

    /**
     * TEST 1 : Séparation des authentifications
     * La connexion utilisateur doit ÉCHOUER avec les identifiants employé
     */
    public function testSeparationAuthentification()
    {
        $resultat = $this->auth->login('sophie.durand@mail.com', 'pwd_sophie');
        $this->assertFalse($resultat, "Les identifiants employé NE devraient PAS fonctionner sur la connexion utilisateur");
    }

    /**
     * TEST 2 : Connexion Employé
     */
    protected function tearDown(): void
    {
        if (isset($this->employeId)) {
            $this->db->prepare("DELETE FROM employe WHERE id_emp = ?", [$this->employeId]);
        }
    }

    public function testConnexionEmploye()
    {
        // Cas d'échec
        $this->assertFalse($this->auth->loginEmploye('testemploye', 'mauvais_mdp'), "La connexion devrait échouer avec un mauvais mot de passe");
        
        // Cas de succès - loginEmploye retourne l'id_poste (2)
        $this->assertEquals(2, $this->auth->loginEmploye('testemploye', 'pwd_test'), "La connexion devrait réussir et retourner l'ID poste 2");
        
        // Vérifier la session
        $this->assertArrayHasKey('auth_employe', $_SESSION, "La clé de session employé devrait être présente");
        $this->assertArrayNotHasKey('auth', $_SESSION, "La clé de session utilisateur NE devrait PAS être présente (Vérification d'isolation)");
    }

    /**
     * TEST 3 : Données du tableau de bord - Covoiturages problématiques
     */
    public function testCovoituragesProblematiquesEnAttente()
    {
         // Vérifier qu'on peut les récupérer
         $problemes = $this->covoitModel->findBadCarpools();
         $this->assertIsArray($problemes);
         
         // Comme nos données de seed peuvent changer, on vérifie juste que la structure est correcte s'il y en a
         if (!empty($problemes)) {
             $element = $problemes[0];
             $this->assertObjectHasProperty('driver_pseudo', $element);
             $this->assertObjectHasProperty('motif', $element);
         }
    }

    /**
     * TEST 4 : Validation d'avis et email
     */
    public function testValidationAvisEtEmail()
    {
        // 1. Configuration : S'assurer qu'on a un avis en attente (id 14 est typique des données seed)
        // Forçons l'ID 14 au statut 2 (Modération)
        $this->avisModel->updateStatus(14, 2);
        
        $enAttente = $this->avisModel->findAllPending();
        $this->assertNotEmpty($enAttente, "Devrait avoir des avis en attente");
        
        $trouve = false;
        foreach($enAttente as $p) {
            if ($p->avis_id == 14) $trouve = true;
        }
        $this->assertTrue($trouve, "L'avis ID 14 devrait être en attente");

        // 2. Valider
        $this->avisModel->updateStatus(14, 1);
        
        // 3. Vérifier en DB
        $avis = $this->avisModel->findOneWithUser(14);
        $this->assertEquals('publié', $avis->statut, "Le statut de l'avis devrait être publié (ID 1)"); 
        // Note : findOneWithUser récupère 'statut' comme chaîne depuis le JOIN. 
        // On doit vérifier les valeurs DDL 'statut_avis'. 
        // DML : 1='publié', 2='modération'.
        // Ma logique utilisait 1 comme Validé.
        // Vérifions l'ID brut juste pour être sûr OU faisons confiance à la correspondance de chaîne si la DB est correcte.
        // En fait findOneWithUser retourne 'statut' (libelle).
        // Si ID=1 est 'publié', alors $avis->statut devrait être 'publié'.
        // Mon code définit status_id = 1.
        
        // Comptons sur la vérification de l'ID d'abord pour la robustesse
        $brut = $this->db->query("SELECT statut_avis_id FROM avis WHERE avis_id=14", null, true);
        $this->assertEquals(1, $brut->statut_avis_id);

        // 4. Vérifier la simulation d'email
        // Puisqu'on n'appelle pas le Controller, l'email n'est pas envoyé par cet appel au Model.
        // Les tests unitent généralement le controller OU le Service.
        // La logique du Controller fait : Update + Mailer->send.
        // Ici on simule ce flux :
        
        $mailer = new \NsAppEcoride\Service\Mailer();
        $sujet = "EcoRide - Avis validé";
        $corps = "Bonjour {$avis->pseudo},<br><br>Votre avis a été validé par notre équipe et est maintenant visible.<br><br>L'équipe EcoRide";
        $res = $mailer->send($avis->email, $sujet, $corps);
        
        $this->assertTrue($res, "La valeur de retour du Mailer devrait être true");
        
        $log = file_get_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt');
        $this->assertStringContainsString('SUCCESS Mailer::send', $log);
        $this->assertStringContainsString($avis->email, $log);
    }

    /**
     * TEST 5 : Refus d'avis et email
     */
    public function testRefusAvisEtEmail()
    {
        // Pause pour éviter le rate limit de Mailtrap (Too many emails per second)
        sleep(2);

        // Simuler connexion employé
        $this->assertEquals(2, $this->auth->loginEmploye('testemploye', 'pwd_test'));
        
        // 1. Configuration : Réinitialiser l'ID 14 à En attente
        $this->avisModel->updateStatus(14, 2);
        
        // 2. Refuser (Statut 'refusé')
        $statutRefus = $this->db->query("SELECT statut_avis_id FROM statut_avis WHERE libelle = 'refusé'", null, true);
        if (!$statutRefus) {
            $this->fail("Le statut 'refusé' n'a pas été trouvé en DB.");
        }
        $idRefus = $statutRefus->statut_avis_id;
        
        $this->avisModel->updateStatus(14, $idRefus);
        
        // 3. Vérifier en DB
        $avis = $this->avisModel->findOneWithUser(14);
        // En supposant que l'ID 3 correspond à un statut de refus. Puisque 'statut' vient du join de la DB, 
        // si l'ID 3 n'est pas seedé avec un libellé, cela pourrait échouer ou retourner un libellé null.
        // Comptons sur la vérification de l'ID principalement.
        
        $brut = $this->db->query("SELECT statut_avis_id FROM avis WHERE avis_id=14", null, true);
        $this->assertEquals($idRefus, $brut->statut_avis_id, "L'ID du statut de l'avis devrait être $idRefus (Refusé)");

        // 4. Vérifier la simulation d'email pour le refus
        $mailer = new \NsAppEcoride\Service\Mailer();
        // Vider le log
        file_put_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt', '');
        
        $sujet = "EcoRide - Avis refusé";
        $corps = "Bonjour {$avis->pseudo},<br><br>Votre avis n'a pas été validé par notre équipe car il ne respecte pas nos conditions d'utilisation.<br><br>L'équipe EcoRide";
        $res = $mailer->send($avis->email, $sujet, $corps);
        $this->assertTrue($res);
        
        $log = file_get_contents(dirname(dirname(__DIR__)) . '/log/email_debug.txt');
        $this->assertStringContainsString('SUCCESS Mailer::send', $log);
    }
}
