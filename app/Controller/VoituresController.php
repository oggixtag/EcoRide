<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\Auth\DbAuth;
use \NsCoreEcoride\HTML\MyForm;
use \App;

/**
 * Contrôleur pour la gestion des voitures des utilisateurs.
 * Gère le CRUD complet des véhicules associés à un utilisateur.
 */
class VoituresController extends AppController
{
    /**
     * Constructeur du contrôleur des voitures.
     * Initialise le modèle Voiture.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Voiture');
    }

    /**
     * Affiche la liste des voitures de l'utilisateur connecté.
     * 
     * @return void Affiche la vue utilisateurs.voitures.index
     */
    public function index()
    {
        $auth = new DbAuth(App::getInstance()->getDb());
        $id = $auth->getConnectedUserId();

        if (!$id) {
            $this->forbidden();
        }

        $voitures = $this->Voiture->getVoituresByUserId($id);
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        $this->render('utilisateurs.voitures.index', compact('voitures', 'error'));
    }

    /**
     * Affiche le formulaire et traite l'ajout d'une nouvelle voiture.
     * Met à jour le rôle de l'utilisateur en Chauffeur-Passager après création.
     * 
     * @return void Affiche la vue utilisateurs.voitures.add ou redirige après création
     */
    public function add()
    {
        $auth = new DbAuth(App::getInstance()->getDb());
        $id = $auth->getConnectedUserId();

        if (!$id) {
            $this->forbidden();
        }

        $message = '';
        $message_type = '';

        if (!empty($_POST)) {
            $data = [
                'modele' => htmlspecialchars($_POST['modele']),
                'immatriculation' => htmlspecialchars($_POST['immatriculation']),
                'energie' => htmlspecialchars($_POST['energie']),
                'couleur' => htmlspecialchars($_POST['couleur']),
                'date_premiere_immatriculation' => htmlspecialchars($_POST['date_premiere_immatriculation']),
                'marque_id' => intval($_POST['marque_id']),
                'utilisateur_id' => $id
            ];

            if ($this->Voiture->create($data)) {
                // MISE A JOUR DU ROLE : Si l'utilisateur ajoute une voiture, il devient Chauffeur-Passager (Role 3)
                // On pourrait vérifier si c'est sa première voiture, mais la demande est "mettre à jour table le champ rôle... role_id=3"
                $this->loadModel('Utilisateur');
                $this->Utilisateur->update($id, ['role_id' => 3]);

                header('Location: index.php?p=utilisateurs.voitures.index');
                exit;
            } else {
                $message = "Erreur lors de l'ajout de la voiture.";
                $message_type = "error";
            }
        }

        $marques = $this->Voiture->getMarques();
        $this->render('utilisateurs.voitures.add', compact('marques', 'message', 'message_type'));
    }

    /**
     * Affiche le formulaire et traite la modification d'une voiture existante.
     * Vérifie que la voiture appartient à l'utilisateur connecté.
     * 
     * @return void Affiche la vue utilisateurs.voitures.edit ou redirige après modification
     */
    public function edit() 
    {
        $auth = new DbAuth(App::getInstance()->getDb());
        $id = $auth->getConnectedUserId();

        if (!$id) {
            $this->forbidden();
        }

        $voiture_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Sécurité : on vérifie que la voiture appartient à l'utilisateur via getVoituresByUserId ou un find avec check
        // Ici on va faire un find et vérifier l'utilisateur_id
        $voiture = $this->Voiture->find($voiture_id);

        if (!$voiture || $voiture->utilisateur_id != $id) {
            // Voiture non trouvée ou n'appartenant pas à l'utilisateur
            $this->forbidden(); 
        }

        $message = '';
        $message_type = '';

        if (!empty($_POST)) {
            $data = [
                'modele' => htmlspecialchars($_POST['modele']),
                'immatriculation' => htmlspecialchars($_POST['immatriculation']),
                'energie' => htmlspecialchars($_POST['energie']),
                'couleur' => htmlspecialchars($_POST['couleur']),
                'date_premiere_immatriculation' => htmlspecialchars($_POST['date_premiere_immatriculation']),
                'marque_id' => intval($_POST['marque_id']),
                'utilisateur_id' => $id
            ];

            if ($this->Voiture->update($voiture_id, $data)) {
                header('Location: index.php?p=utilisateurs.voitures.index');
                exit;
            } else {
                $message = "Erreur lors de la modification.";
                $message_type = "error";
            }
        }

        $marques = $this->Voiture->getMarques();
        $this->render('utilisateurs.voitures.edit', compact('voiture', 'marques', 'message', 'message_type'));
    }

    /**
     * Supprime une voiture de l'utilisateur connecté.
     * Vérifie la propriété et les dépendances (covoiturages liés) avant suppression.
     * 
     * @return void Redirige vers la liste des voitures
     */
    public function delete()
    {
        $auth = new DbAuth(App::getInstance()->getDb());
        $id = $auth->getConnectedUserId();

        if (!$id) {
            $this->forbidden();
        }

        if (!empty($_POST)) {
            $voiture_id = intval($_POST['id']);
            
            // SÉCURITÉ : Vérifier la propriété d'abord
            $voiture = $this->Voiture->find($voiture_id);
            if (!$voiture || $voiture->utilisateur_id != $id) {
                 $this->forbidden();
            }

            // VÉRIFIER LES DÉPENDANCES
            if ($this->Voiture->hasCovoiturages($voiture_id)) {
                // Impossible de supprimer, rediriger avec erreur
                header('Location: index.php?p=utilisateurs.voitures.index&error=constraint');
                exit;
            }

            if ($this->Voiture->deleteCar($voiture_id, $id)) {
                // Succès
            }
        }

        header('Location: index.php?p=utilisateurs.voitures.index');
        exit;
    }
}
