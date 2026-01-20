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
        $employes = $this->Employe->all();
        $this->render('admin.employe.index', compact('employes'));
    }

    public function add()
    {
        // si les données ont été passé en parametre, on le sauvegarde
        if (!empty($_POST)) {
            $result = $this->Employe->insert(
                [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'mail' => $_POST['mail'],
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
                    'mail' => $_POST['mail'],
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
