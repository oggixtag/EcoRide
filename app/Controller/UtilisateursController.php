<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\Auth\DbAuth;
use \NsCoreEcoride\HTML\MyForm;
use \App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Contrôleur pour la gestion des utilisateurs.
 * Gère l'authentification, l'inscription, le profil et l'historique.
 */
class UtilisateursController extends AppController
{
    /**
     * Constructeur du contrôleur utilisateurs.
     * Initialise le modèle Utilisateur.
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadModel('Utilisateur');
    }

    /**
     * Affiche le tableau de bord de l'utilisateur authentifié.
     * Gère l'affichage différent selon le type d'auth (visiteur ou utilisateur).
     * 
     * @return void Affiche la vue utilisateurs.profile.index ou redirige vers login
     */
    public function index()
    {
        // Récupérer l'ID utilisateur depuis la session
        $auth = new DbAuth(App::getInstance()->getDb());
        $utilisateur_id = $auth->getConnectedUserId();
        $auth_type = $auth->getAuthType();

        if (!$utilisateur_id) {
            header('Location: index.php?p=utilisateurs.login');
            exit;
        }

        if ($auth_type === 'visiteur') {
            $visiteur = $this->Utilisateur->getVisiteur($utilisateur_id);
            $statut_mail_id = $visiteur->statut_mail_id;
            
            $this->render('utilisateurs.profile.index', compact('visiteur', 'auth_type', 'statut_mail_id'));
        } else {
            $utilisateur = $this->Utilisateur->find($utilisateur_id);
            $role = $this->Utilisateur->getRoleForUser($utilisateur_id);
            $avis = $this->Utilisateur->getAvisForUser($utilisateur_id);
            $voitures = $this->Utilisateur->getVoituresForUser($utilisateur_id);
            $covoiturages = $this->Utilisateur->getCovoituragesForUser($utilisateur_id);
            $reservations = $this->Utilisateur->findParticipations($utilisateur_id);
            
            // FILTRE: Ne garder que les trajets futurs (>= Aujoud'hui) pour la vue principale
            // Les passés vont dans l'historique.
            $today = date('Y-m-d');
            
            $covoiturages = array_filter($covoiturages, function($c) use ($today) {
                return $c->date_depart >= $today; // Ou > ? Généralement on garde ceux du jour.
            });
            
            $reservations = array_filter($reservations, function($r) use ($today) {
                return $r->date_depart >= $today;
            });
            
            // Vérifier les indicateurs d'historique (Garder la logique : vérifier si UN passé existe)
            $hist_ch = $this->Utilisateur->getHistoriqueCovoiturages($utilisateur_id);
            $has_history_chauffeur = !empty($hist_ch);
            
            $hist_pa = $this->Utilisateur->getHistoriqueParticipations($utilisateur_id);
            $has_history_passager = !empty($hist_pa);

            $this->render('utilisateurs.profile.index', compact('utilisateur', 'role', 'avis', 'voitures', 'covoiturages', 'reservations', 'auth_type', 'has_history_chauffeur', 'has_history_passager'));
        }
    }

    /**
     * Affiche la page de connexion et gère l'authentification.
     * Traite les données POST pour tenter une connexion.
     * 
     * @return void Affiche la vue utilisateurs.login ou redirige après connexion
     */
    public function login()
    {
        $errors = false;
        $message = '';
        $message_type = '';

        if (!empty($_POST)) {

            $auth = new DbAuth(App::getInstance()->getDb());

            if ($auth->login($_POST['pseudo'], $_POST['password'])) {
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
     * Déconnecte l'utilisateur et redirige vers la page d'accueil.
     * Détruit la session et supprime le cookie de session.
     * 
     * @return void Redirige vers index.php
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
     * Gère la récupération de mot de passe.
     * Cherche l'utilisateur par email ou pseudo et affiche un message.
     * 
     * @return void Affiche la vue utilisateurs.login avec message de récupération
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
    /**
     * Gère l'inscription d'un nouveau visiteur.
     * Valide les données, crée le compte visiteur et envoie un email de validation.
     * 
     * @return void Affiche la vue utilisateurs.inscrir ou redirige après création
     */
    public function inscrir()
    {
        $message = '';
        $message_type = '';
        $data = [];

        if (!empty($_POST)) {
            $pseudo = htmlspecialchars(trim($_POST['pseudo']));
            $email = htmlspecialchars(strtolower(trim($_POST['email'])));
            $password = trim($_POST['password']);
            
            // Simuler data pour pré-remplir le form en cas d'échec
            $data = ['pseudo' => $pseudo, 'email' => $email];

            // Validation basique
            if (empty($pseudo) || empty($email) || empty($password)) {
                 $message = "Tous les champs sont obligatoires.";
                 $message_type = "error";
            } elseif (!$this->Utilisateur->isPseudoUnique($pseudo)) {
                 $message = "Ce pseudo est déjà utilisé.";
                 $message_type = "error";
            } elseif (!$this->Utilisateur->isEmailUnique($email)) {
                 $message = "Cet email est déjà utilisé.";
                 $message_type = "error";
            } else {
                 // Création du compte visiteur
                 // Création du compte visiteur
                 
                 $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                 
                 if ($this->Utilisateur->createVisiteur($pseudo, $email, $passwordHashed)) {

                     
                     // Envoi de l'email de validation
                     $this->sendValidationEmail($email, $pseudo);

                     // Connexion automatique
                     $auth = new DbAuth(App::getInstance()->getDb());
                     if ($auth->login($pseudo, $password)) {
                         // Redirection vers le dashboard
                         header('Location: index.php?p=utilisateurs.index'); 
                         exit;
                     } else {
                         // Fallback si le login échoue (ne devrait pas arriver)
                         header('Location: index.php?p=utilisateurs.login&created=1');
                         exit;
                     }
                 } else {
                     $message = "Erreur lors de la création du compte.";
                     $message_type = "error";
                 }
            }
        }

        $form = new MyForm($data);
        $this->render('utilisateurs.inscrir', compact('form', 'message', 'message_type'));
    }

    /**
     * Vérification AJAX de l'unicité d'un pseudo ou email.
     * Utilisé pour la validation en temps réel du formulaire d'inscription.
     * 
     * @return void Envoie une réponse JSON avec le résultat de la vérification
     */
    public function verificationUnique()
    {
        header('Content-Type: application/json');
        
        $field = $_GET['field'] ?? '';
        $value = $_GET['value'] ?? '';
        
        if ($field === 'pseudo') {
            $unique = $this->Utilisateur->isPseudoUnique($value);
            echo json_encode(['unique' => $unique]);
        } elseif ($field === 'email') {
            $unique = $this->Utilisateur->isEmailUnique($value);
            echo json_encode(['unique' => $unique]);
        } else {
            echo json_encode(['error' => 'Champ invalide']);
        }
        exit;
    }
    
    /**
     * Valide l'email du visiteur connecté.
     * Passe le statut_mail_id à 2 (validé) dans la table visiteur.
     * 
     * @return void Redirige vers le profil utilisateur
     */
    public function validerEmail()
    {

        
        // On récupère le visiteur connecté (auth_type doit être visiteur)
        $auth = new DbAuth(App::getInstance()->getDb());
        $auth_type = $auth->getAuthType();
        $id = $auth->getConnectedUserId();

        if ($auth_type === 'visiteur' && $id) {
            // Mettre à jour le statut
            if ($this->Utilisateur->updateVisiteurStatut($id, 2)) {
                // Redirection
                header('Location: index.php?p=utilisateurs.index');
                exit;
            }

        
        }
        
        
        // Si erreur ou déjà validé
        header('Location: index.php?p=utilisateurs.index');
        exit;
    }

    /**
     * Finalise l'inscription d'un visiteur en utilisateur complet.
     * Transfère les données de visiteur_utilisateur vers utilisateur.
     * 
     * @return void Redirige vers le profil utilisateur après la mise à niveau
     */
    public function finaliserInscription()
    {

        
        $auth = new DbAuth(App::getInstance()->getDb());
        $auth_type = $auth->getAuthType();
        $id = $auth->getConnectedUserId();

        
        
        if (!$auth->isConnected() || $auth_type !== 'visiteur') {

            $this->forbidden();
        }

        if (!empty($_POST)) {
            // Récupérer les infos du visiteur pour ne pas avoir à les repasser en post
            $visiteur = $this->Utilisateur->getVisiteur($id);
            if (!$visiteur) {
                // Erreur critique
                $this->forbidden();
            }

            // Validation des champs
            // Nom, Prénom, Téléphone, Adresse, Date de Naissance
            // TODO : Ajouter validation robuste
            
            $data = [
                'nom' => htmlspecialchars($_POST['nom']),
                'prenom' => htmlspecialchars($_POST['prenom']),
                'telephone' => htmlspecialchars($_POST['telephone']),
                'adresse' => htmlspecialchars($_POST['adresse']),
                'date_naissance' => htmlspecialchars($_POST['date_naissance']),
                'role_id' => 2 // Par défaut
                // 'photo' => ... gestion upload 
            ];
            
            if ($this->Utilisateur->upgradeVisiteurToUser($id, $data)) {
                 // Mise à jour de la session pour passer en 'utilisateur'
                 $_SESSION['auth_type'] = 'utilisateur';
                 
                 // On récupère le nouvel ID utilisateur grâce au pseudo
                 $user = $this->Utilisateur->findByPseudo($visiteur->pseudo);
                 
                 if ($user) {
                      $_SESSION['auth'] = $user->utilisateur_id;
                      $_SESSION['auth_type'] = 'utilisateur'; 
                 }
                 
                 header('Location: index.php?p=utilisateurs.index');
                 exit;
            } else {

                // Erreur
            }
        }
    }
    /**
     * Envoie l'email de validation à un nouveau visiteur.
     * Utilise le service Mailer pour l'envoi.
     * 
     * @param string $email Adresse email du destinataire
     * @param string $pseudo Pseudo de l'utilisateur pour personnaliser l'email
     * @return bool True si l'email a été envoyé avec succès, false sinon
     */
    private function sendValidationEmail($email, $pseudo)
    {
        require_once ROOT . '/app/Service/Mailer.php';
        $mailer = new \NsAppEcoride\Service\Mailer();
        
        $subject = 'Bienvenue sur EcoRide - Confirmez votre email';
        $body = "
            <h1>Bienvenue $pseudo !</h1>
            <p>Merci de vous être inscrit sur EcoRide.</p>
            <p>Pour finaliser votre inscription, veuillez confirmer votre voir email en cliquant sur le lien suivant (fictif pour le moment) :</p>
            <p><a href='#'>Confirmer mon email</a></p>
            <br>
            <p>L'équipe EcoRide</p>
        ";
        $altBody = "Bienvenue $pseudo ! Merci de confirmer votre email.";

        return $mailer->send($email, $subject, $body, $altBody);
    }
    /**
     * Modifie les informations du profil utilisateur.
     * Gère la mise à jour des données personnelles et du rôle.
     * 
     * @return void Affiche la vue utilisateurs.profile.edit ou redirige après mise à jour
     */
    public function edit()
    {
        $auth = new DbAuth(App::getInstance()->getDb());
        $id = $auth->getConnectedUserId();

        if (!$id) {
            $this->forbidden();
        }

        $utilisateur = $this->Utilisateur->find($id);
        $message = '';
        $message_type = '';

        if (!empty($_POST)) {
            // Validation et Update
            // Determine Role ID from checkboxes
            // Passager (role_passager) = 2
            // Chauffeur (role_chauffeur) = 1
            // Both checked = 3 (Chauffeur-Passager)
            
            $is_passager = isset($_POST['role_passager']); // Checked box sends value
            // $is_chauffeur = isset($_POST['role_chauffeur']); // Désactivé, on vérifie les voitures

            // Vérifier si l'utilisateur a des voitures pour déterminer le rôle Chauffeur
            $voitures = $this->Utilisateur->getVoituresForUser($id);
            $has_cars = !empty($voitures);
            
            $is_chauffeur = $has_cars;

            $role_id = 2; // Default fallback (Passager)
            
            if ($is_chauffeur) {
                $role_id = 3; // Si a des voitures, on passe en "Chauffeur-Passager" (3) automatiquement
            } else {
                // Si pas de voiture, on reste Passager (2) même si on essayait d'être chauffeur
                $role_id = 2; 
            }

            $data = [
                'nom' => htmlspecialchars($_POST['nom']),
                'prenom' => htmlspecialchars($_POST['prenom']),
                'telephone' => htmlspecialchars($_POST['telephone']),
                'adresse' => htmlspecialchars($_POST['adresse']),
                'date_naissance' => htmlspecialchars($_POST['date_naissance']),
                'role_id' => $role_id 
            ];

            // Mettre à jour les infos utilisateur
            if ($this->Utilisateur->update($id, $data)) {
                $message = "Profil mis à jour avec succès.";
                $message_type = "success";
                
                // Mettre à jour les préférences
                $this->Utilisateur->clearPreferences($id);
                
                // Gérer les cases à cocher standard (fumeur, animaux)
                if (isset($_POST['pref_fumeur'])) {
                    $this->Utilisateur->addPreference($id, 'Fumeur');
                } else {
                     $this->Utilisateur->addPreference($id, 'Non Fumeur');
                }

                if (isset($_POST['pref_animaux'])) {
                    $this->Utilisateur->addPreference($id, 'Accepte les animaux');
                } else {
                    $this->Utilisateur->addPreference($id, 'Pas d\'animaux');
                }
                
                // Gérer les préférences personnalisées
                if (!empty($_POST['custom_prefs'])) {
                    $customs = explode(',', $_POST['custom_prefs']);
                    foreach ($customs as $pref) {
                        $pref = trim($pref);
                        if (!empty($pref)) {
                            $this->Utilisateur->addPreference($id, $pref);
                        }
                    }
                }
                
                // Rafraîchir les données
                $utilisateur = $this->Utilisateur->find($id);

            } else {
                $message = "Erreur lors de la mise à jour.";
                $message_type = "error";
            }
        }

        $preferences = $this->Utilisateur->getPreferences($id);
        
        // Préparer les préférences pour la vue (tableau simple de libellés)
        $user_prefs = [];
        foreach ($preferences as $p) {
            $user_prefs[] = $p->libelle;
        }

        $voitures = $this->Utilisateur->getVoituresForUser($id);
        $has_cars = !empty($voitures);

        $this->render('utilisateurs.profile.edit', compact('utilisateur', 'user_prefs', 'message', 'message_type', 'has_cars'));
    }
    /**
     * Affiche l'historique des covoiturages et participations passés.
     * Récupère les trajets terminés en tant que chauffeur et passager.
     * 
     * @return void Affiche la vue utilisateurs.profile.historique
     */
    public function historique()
    {
         $auth = new DbAuth(App::getInstance()->getDb());
         $utilisateur_id = $auth->getConnectedUserId();
         
         if (!$utilisateur_id) {
             $this->forbidden();
         }
         
         $utilisateur = $this->Utilisateur->find($utilisateur_id);
         
         // Récupérer les données
         $historique_chauffeur = $this->Utilisateur->getHistoriqueCovoiturages($utilisateur_id);
         $historique_passager = $this->Utilisateur->getHistoriqueParticipations($utilisateur_id);
         
         $this->render('utilisateurs.profile.historique', compact('utilisateur', 'historique_chauffeur', 'historique_passager'));
    }
    /**
     * Valide les données d'inscription (Email et Mot de passe).
     * Méthode publique statique pour permettre les tests unitaires.
     * 
     * @param string $email
     * @param string $emailConfirm
     * @param string $password
     * @return array Tableau d'erreurs (vide si valide)
     */
    public static function validateRegistrationData($email, $emailConfirm, $password)
    {
        $errors = [];

        // 1. Validation Format Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email n'est pas valide.";
        }

        // 2. Validation Correspondance Email
        if ($email !== $emailConfirm) {
            $errors[] = "Les adresses email ne correspondent pas.";
        }

        // 3. Validation Complexité Mot de Passe
        // Au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
        }

        return $errors;
    }
}
