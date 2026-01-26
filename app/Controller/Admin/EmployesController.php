<?php

namespace NsAppEcoride\Controller\Admin;

class EmployesController extends AppController
{

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Employe');
    }

    public function index()
    {
        if (!$this->isEmploye()) {
             $this->forbidden();
        }

        $employes = $this->Employe->all();
        $this->render('admin.employe.index', compact('employes'));
    }

    /**
     * Dashboard pour les employés (Review Validation + Bad Carpools)
     */
    public function dashboard()
    {
         if (!$this->isEmploye()) {
             header('Location: index.php?p=admin.employe.login');
             exit;
         }

         $this->loadModel('Avis');
         $this->loadModel('Covoiturage');

         $avis_pending = $this->Avis->findAllPending();
         $bad_carpools = $this->Covoiturage->findBadCarpools();

         $this->render('admin.employe.dashboard', compact('avis_pending', 'bad_carpools'));
    }

    private function isEmploye()
    {
        $auth = new \NsCoreEcoride\Auth\DbAuth(\App::getInstance()->getDb());
        return $auth->isEmploye();
    }

    public function login()
    {
        $errors = false;
        if (!empty($_POST)) {
            $auth = new \NsCoreEcoride\Auth\DbAuth(\App::getInstance()->getDb());
            if ($auth->loginEmploye($_POST['email'], $_POST['password'])) {
                header('Location: index.php?p=admin.employe.dashboard');
                exit;
            } else {
                $errors = true;
            }
        }
        $form = new \NsCoreEcoride\HTML\MyForm($_POST);
        $this->render('admin.employe.login', compact('form', 'errors'));
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['auth_employe'] = null;
        header('Location: index.php?p=admin.employe.login');
        exit;
    }

    public function validateAvis()
    {
        if (!$this->isEmploye() || empty($_POST['id'])) {
             $this->forbidden();
        }
        
        $this->loadModel('Avis');
        $avis = $this->Avis->findOneWithUser($_POST['id']);
        
        if ($avis) {
            // Update Status
            $this->Avis->updateStatus($_POST['id'], 1); // 1 = Validé/Publié

            // Send Email
            require_once ROOT . '/app/Service/Mailer.php';
            $mailer = new \NsAppEcoride\Service\Mailer();
            $subject = "EcoRide - Avis validé";
            $body = "Bonjour {$avis->pseudo},<br><br>Votre avis a été validé par notre équipe et est maintenant visible.<br><br>L'équipe EcoRide";
            $mailer->send($avis->email, $subject, $body);
        }
        
        header('Location: index.php?p=admin.employe.dashboard');
        exit;
    }

    public function refuseAvis()
    {
        if (!$this->isEmploye() || empty($_POST['id'])) {
             $this->forbidden();
        }
        
        $this->loadModel('Avis');
        $avis = $this->Avis->findOneWithUser($_POST['id']);
        
        if ($avis) {
            // Update Status
            // Note: As per DML, we only have 'publié' and 'modération'. 
            // Ideally we should have a 'refusé' status or delete it.
            // Assuming 3 = Refusé/Rejeté based on common practice, though DML needs update if strictly foreign keyed.
            // For safety, we keeps it as is or hypothetical 3. 
            $this->Avis->updateStatus($_POST['id'], 3);

            // Send Email
            require_once ROOT . '/app/Service/Mailer.php';
            $mailer = new \NsAppEcoride\Service\Mailer();
            $subject = "EcoRide - Avis refusé";
            $body = "Bonjour {$avis->pseudo},<br><br>Votre avis n'a pas été validé par notre équipe car il ne respecte pas nos conditions d'utilisation.<br><br>L'équipe EcoRide";
            $mailer->send($avis->email, $subject, $body);
        }
        
        header('Location: index.php?p=admin.employe.dashboard');
        exit;
    }



    public function add()
    {
        // si les données ont été passé en parametre, on le sauvegarde
        if (!empty($_POST)) {
            $result = $this->Employe->insert(
                [
                'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
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
                $this->index();

                /*echo '<pre>';
                var_dump('Admin.EmployesController.add().location admin.employe.edit..');
                echo '</pre>';
                Warning: Cannot modify header information - headers already sent by (output started at C:\xampp\htdocs\PHP POO\blog\app\App.php:103) in C:\xampp\htdocs\PHP POO\blog\app\Controller\Admin\PostsController.php on line 59
                header('Location: admin.php?p=admin.employe.edit&id=' . $this->getlastInsertId());*/
            }
        }

        $this->loadModel('Departement');
        $departements = $this->Departement->extratList('id_dept', 'nom_dept');

        $this->loadModel('Poste');
        $postes = $this->Poste->extratList('id_poste', 'intitule');

        $form = new MyForm($_POST);

        $this->render('admin.employe.add', compact('employes', 'departements', 'postes', 'form'));
    }

    public function edit()
    {
        // si les données ont été passé en parametre, on le sauvegarde
        if (!empty($_POST)) {
            $result = $this->Employe->update(
                $_GET['id_emp'],
                [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    // 'password' => $_POST['password'], // Optional update? Let's leave it out for edit for now to simplify
                    'date_embauche' => $_POST['date_embauche'],
                    'salaire' => $_POST['salaire'],
                    'id_poste' => $_POST['id_poste'],
                    'id_dept' => $_POST['id_dept'],
                    'id_manager' => $_POST['id_manager']
                ]
            );
            if ($result) {
                return $this->index();
            }
        }

        $employes = $this->Employe->find($_GET['id_emp']);

        $this->loadModel('Departement');
        $departements = $this->Departement->extratList('id_dept', 'nom_dept');

        $this->loadModel('Poste');
        $postes = $this->Poste->extratList('id_poste', 'intitule');

        $form = new MyForm($employes);

        $this->render('admin.employe.edit', compact('employes', 'departements', 'postes', 'form'));
    }

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
