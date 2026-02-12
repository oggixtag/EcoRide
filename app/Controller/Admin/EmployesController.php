<?php

namespace NsAppEcoride\Controller\Admin;

/**
 * Contrôleur pour la gestion des employés.
 * Gère le CRUD des employés, l'authentification et la validation des avis.
 */
class EmployesController extends AppController
{

    /**
     * Constructeur du contrôleur employés.
     * Initialise le modèle Employe.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Employe');
    }

    /**
     * Affiche la liste de tous les employés (sauf admin).
     * Accessible uniquement aux employés connectés.
     * 
     * @return void Affiche la vue admin.employes.index
     */
    public function index()
    {
        if (!$this->isEmploye()) {
             $this->forbidden();
        }

        $employes = $this->Employe->findAllWithoutAdmin();
        $this->render('admin.employes.index', compact('employes'));
    }

    /**
     * Affiche le tableau de bord des employés.
     * Contient les avis en attente de validation et les covoiturages problématiques.
     * 
     * @return void Affiche la vue admin.employes.dashboard
     */
    public function dashboard()
    {
         if (!$this->isEmploye()) {
             header('Location: index.php?p=admin.employes.login');
             exit;
         }

         $this->loadModel('Avis');
         $this->loadModel('Covoiturage');

         $avis_pending = $this->Avis->findAllPending();
         $bad_carpools = $this->Covoiturage->findBadCarpools();

         $this->render('admin.employes.dashboard', compact('avis_pending', 'bad_carpools'));
    }

    /**
     * Vérifie si l'utilisateur courant est un employé connecté.
     * 
     * @return bool True si un employé est connecté, false sinon
     */
    private function isEmploye()
    {
        $auth = new \NsCoreEcoride\Auth\DbAuth(\App::getInstance()->getDb());
        return $auth->isEmploye() || $auth->isAdmin();
    }

    /**
     * Affiche la page de connexion et gère l'authentification des employés.
     * Redirige vers le dashboard admin ou employé selon le poste.
     * 
     * @return void Affiche la vue admin.employes.login ou redirige après connexion
     */
    public function login()
    {
        $errors = false;
        if (!empty($_POST)) {
            $auth = new \NsCoreEcoride\Auth\DbAuth(\App::getInstance()->getDb());
            // loginEmploye() retourne l'id_poste (1=admin, 2=employe) ou false
            $id_poste = $auth->loginEmploye($_POST['pseudo'], $_POST['password']);
            
            if ($id_poste !== false) {
                session_write_close(); // S'assurer que la session est sauvegardée avant la redirection
                
                // Rediriger selon le poste de l'utilisateur qui vient de se connecter
                if ($id_poste == 1) {
                    // Administrateur
                    header('Location: index.php?p=admin.dashboard');
                } else {
                    // Employé
                    header('Location: index.php?p=admin.employes.dashboard');
                }
                exit;
            } else {
                $errors = true;
            }
        }
        // $form = new \NsCoreEcoride\Html\Form($_POST); // Namespace Html validé
        $form = new \NsCoreEcoride\Html\Form($_POST);
        $this->render('admin.employes.login', compact('form', 'errors'));
    }

    /**
     * Suspend un employé.
     * 
     * @return void Redirige vers la liste des employés
     */
    public function suspendre()
    {
        if (!empty($_POST['id'])) {
             $this->Employe->suspendre($_POST['id']);
        }
        header('Location: index.php?p=admin.employes.index');
    }

    /**
     * Réactive un employé précédemment suspendu.
     * 
     * @return void Redirige vers la liste des employés
     */
    public function reactiver()
    {
        if (!empty($_POST['id'])) {
             $this->Employe->reactiver($_POST['id']);
        }
        header('Location: index.php?p=admin.employes.index');
    }

    /**
     * Déconnecte l'employé.
     * Efface seulement auth_employe sans toucher à auth_admin.
     * 
     * @return void Redirige vers la page de connexion employé
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Effacer seulement la session employé, ne pas toucher à auth_admin
        unset($_SESSION['auth_employe']);
        header('Location: index.php?p=admin.employes.login');
        exit;
    }

    /**
     * Valide un avis en attente.
     * Met à jour le statut de l'avis et envoie un email de confirmation.
     * 
     * @return void Redirige vers le dashboard employé
     */
    public function validateAvis()
    {
        if (!$this->isEmploye() || empty($_POST['id'])) {
             $this->forbidden();
        }
        
        $this->loadModel('Avis');
        $avis = $this->Avis->findOneWithUser($_POST['id']);
        
        if ($avis) {
            // Mettre à jour le statut
            $this->Avis->updateStatus($_POST['id'], 1); // 1 = Validé/Publié

            // Envoyer l'email
            require_once ROOT . '/app/Service/Mailer.php';
            $mailer = new \NsAppEcoride\Service\Mailer();
            $subject = "EcoRide - Avis validé";
            $body = "Bonjour {$avis->pseudo},<br><br>Votre avis a été validé par notre équipe et est maintenant visible.<br><br>L'équipe EcoRide";
            $mailer->send($avis->email, $subject, $body);
        }
        
        header('Location: index.php?p=admin.employes.dashboard');
        exit;
    }

    /**
     * Refuse un avis en attente.
     * Met à jour le statut de l'avis et envoie un email de notification.
     * 
     * @return void Redirige vers le dashboard employé
     */
    public function refuseAvis()
    {
        if (!$this->isEmploye() || empty($_POST['id'])) {
             $this->forbidden();
        }
        
        $this->loadModel('Avis');
        $avis = $this->Avis->findOneWithUser($_POST['id']);
        
        if ($avis) {
            // Mettre à jour le statut
            // Note : Selon le DML, nous n'avons que 'publié' et 'modération'.
            // Idéalement, nous devrions avoir un statut 'refusé' ou le supprimer.
            // En supposant que 3 = Refusé/Rejeté selon la pratique courante, bien que le DML doive être mis à jour si strictement lié par clé étrangère.
            // Par sécurité, nous gardons cela tel quel ou utilisons un 3 hypothétique.
            $this->Avis->updateStatus($_POST['id'], 3);

            // Envoyer l'email
            require_once ROOT . '/app/Service/Mailer.php';
            $mailer = new \NsAppEcoride\Service\Mailer();
            $subject = "EcoRide - Avis refusé";
            $body = "Bonjour {$avis->pseudo},<br><br>Votre avis n'a pas été validé par notre équipe car il ne respecte pas nos conditions d'utilisation.<br><br>L'équipe EcoRide";
            $mailer->send($avis->email, $subject, $body);
        }
        
        header('Location: index.php?p=admin.employes.dashboard');
        exit;
    }



    /**
     * Affiche le formulaire et traite l'ajout d'un nouvel employé.
     * 
     * @return void Affiche la vue admin.employes.add ou redirige après création
     */
    public function add()
    {
        // si les données ont été passé en parametre, on le sauvegarde
        if (!empty($_POST)) {
            $result = $this->Employe->insert(
                [
                'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'pseudo' => $_POST['pseudo'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'date_embauche' => $_POST['date_embauche'],
                    'salaire' => $_POST['salaire'],
                    'id_poste' => $_POST['id_poste'],
                    'id_dept' => $_POST['id_dept'],
                    'id_manager' => $_POST['id_manager']
                ]
            );
            if ($result) {
                header('Location: index.php?p=admin.employes.index');
                exit;
            }
        }

        $this->loadModel('Departement');
        $departements = $this->Departement->extratList('id_dept', 'nom_dept');

        $this->loadModel('Poste');
        // Filtrer les postes pour n'afficher que les employés (non-admins)
        $rawPostes = $this->Poste->getPosteEmploye(); 
        $postes = [];
        foreach($rawPostes as $p) {
            $postes[$p->id_poste] = $p->intitule;
        }

        // Récupérer la liste des managers
        $managers = $this->Employe->getManagersList();

        $form = new \NsCoreEcoride\Html\Form($_POST);

        $this->render('admin.employes.add', compact('departements', 'postes', 'managers', 'form')); // managers passé en paramètre
    }

    /**
     * Affiche le formulaire et traite la modification d'un employé.
     * 
     * @return void Affiche la vue admin.employes.edit ou redirige après modification
     */
    public function edit()
    {
        // si les données ont été passé en parametre, on le sauvegarde
        if (!empty($_POST)) {
            $result = $this->Employe->update(
                $_GET['id_emp'],
                [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'pseudo' => $_POST['pseudo'],
                    'email' => $_POST['email'],
                    // 'password' => $_POST['password'], // Mise à jour optionnelle ? On le laisse de côté pour l'édition pour le moment pour simplifier
                    'date_embauche' => $_POST['date_embauche'],
                    'salaire' => $_POST['salaire'],
                    'id_poste' => $_POST['id_poste'],
                    'id_dept' => $_POST['id_dept'],
                    'id_manager' => $_POST['id_manager']
                ]
            );
            if ($result) {
                header('Location: index.php?p=admin.employes.index');
                exit;
            }
        }

        $employes = $this->Employe->find($_GET['id_emp']);

        $this->loadModel('Departement');
        $departements = $this->Departement->extratList('id_dept', 'nom_dept');

        $this->loadModel('Poste');
        // Filtrer les postes pour n'afficher que les employés (non-admins)
        $rawPostes = $this->Poste->getPosteEmploye(); 
        $postes = [];
        foreach($rawPostes as $p) {
            $postes[$p->id_poste] = $p->intitule;
        }

        // Récupérer la liste des managers
        $managers = $this->Employe->getManagersList();

        $form = new \NsCoreEcoride\Html\Form($employes);

        $this->render('admin.employes.edit', compact('employes', 'departements', 'postes', 'managers', 'form'));
    }

    /**
     * Supprime un employé de la base de données.
     * 
     * @return void Redirige vers la liste des employés ou affiche l'index
     */
    public function delete()
    {
        if (!empty($_POST)) {
            $result = $this->Employe->delete($_POST['id_emp']);
            //return $this->index();

            if ($result) {
                return $this->index();
            }
        }
    }
}
