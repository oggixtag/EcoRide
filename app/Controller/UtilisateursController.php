<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\Auth\DbAuth;
use \NsCoreEcoride\HTML\MyForm;
use \App;

class UtilisateursController extends AppController
{
    public function __construct()
    {
        parent::__construct();

        $this->loadModel('Utilisateur');
    }

    /**
     * Affiche le tableau de bord de l'utilisateur authentifié
     */
    public function index()
    {
        // Récupérer l'ID utilisateur depuis la session
        $utilisateur_id = $_SESSION['auth'] ?? null;

        if (!$utilisateur_id) {
            $this->forbidden();
        }

        // Récupérer les informations de l'utilisateur connecté
        $utilisateur = $this->Utilisateur->find($utilisateur_id);
        $role = $this->Utilisateur->getRoleForUser($utilisateur_id);
        $avis = $this->Utilisateur->getAvisForUser($utilisateur_id);
        $voitures = $this->Utilisateur->getVoituresForUser($utilisateur_id);
        $covoiturages = $this->Utilisateur->getCovoituragesForUser($utilisateur_id);
        $reservations = $this->Utilisateur->findParticipations($utilisateur_id);

        $this->render('utilisateurs.index', compact('utilisateur', 'role', 'avis', 'voitures', 'covoiturages', 'reservations'));
    }

    /**
     * Affiche la page de connexion et gère l'authentification
     */
    public function login()
    {
        $errors = false;
        $message = '';
        $message_type = '';

        if (!empty($_POST)) {

            $auth = new DbAuth(App::getInstance()->getDb());

            if ($auth->login($_POST['username'], $_POST['password'])) {
                header('Location: index.php?p=utilisateurs.index');
                exit;
            } else {
                $errors = true;
            }
        }

        $form = new MyForm($_POST);
        $this->render('utilisateurs.login', compact('form', 'errors', 'message', 'message_type'));
    }

    /**
     * Déconnecte l'utilisateur et redirige vers la page d'accueil
     */
    public function logout()
    {
        // Détruire les données de session et rediriger vers l'accueil
        if (session_status() === PHP_SESSION_ACTIVE) {
            // Vider toutes les variables de session
            $_SESSION = [];

            // Si on veut supprimer le cookie de session
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            // Finalement détruire la session
            session_destroy();
        }

        header('Location: index.php');
        exit;
    }

    /**
     * Gère la récupération de mot de passe
     * Cherche l'utilisateur par email ou pseudo et affiche un message
     */
    public function recupererPassword()
    {
        $message = '';
        $message_type = '';
        $recovery_method = '';
        $recovery_input = '';

        if (!empty($_POST)) {
            // Récupérer les données du formulaire
            $recovery_method = isset($_POST['recovery_method']) ? $_POST['recovery_method'] : 'email';
            $recovery_input = isset($_POST['recovery_input']) ? trim($_POST['recovery_input']) : '';

            if (empty($recovery_input)) {
                $message = 'Veuillez entrer un ' . ($recovery_method === 'email' ? 'email' : 'pseudo');
                $message_type = 'error';
            } else {
                // Chercher l'utilisateur par email ou pseudo
                if ($recovery_method === 'email') {
                    $utilisateur = $this->Utilisateur->findByEmail($recovery_input);
                } else {
                    $utilisateur = $this->Utilisateur->findByPseudo($recovery_input);
                }

                if ($utilisateur) {
                    // Utilisateur trouvé - dans une vraie application, on enverrait un email
                    // Pour cette démonstration, on affiche un message de succès
                    $message = 'Un email de récupération a été envoyé à l\'adresse associée à votre compte.';
                    $message_type = 'success';

                    // TODO: Implémenter l'envoi d'email avec un lien de réinitialisation
                    // Pour l'instant, on affiche juste un message
                } else {
                    $message = 'Aucun utilisateur trouvé avec ce ' . ($recovery_method === 'email' ? 'email' : 'pseudo');
                    $message_type = 'error';
                }
            }
        }

        $form = new MyForm($_POST);
        $this->render('utilisateurs.login', compact('form', 'message', 'message_type', 'recovery_method', 'recovery_input'));
    }
}
