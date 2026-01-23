<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\Auth\DbAuth;
use \NsCoreEcoride\HTML\MyForm;
use \App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
        $auth = new DbAuth(App::getInstance()->getDb());
        $utilisateur_id = $auth->getConnectedUserId();
        $auth_type = $auth->getAuthType();

        if (!$utilisateur_id) {
            $this->forbidden();
        }

        if ($auth_type === 'visiteur') {
            $visiteur = $this->Utilisateur->getVisiteur($utilisateur_id);
            $statut_mail_id = $visiteur->statut_mail_id;
            
            $this->render('utilisateurs.profile.index', compact('visiteur', 'auth_type', 'statut_mail_id'));
        } else {
            // Logique existante pour un utilisateur complet
            $utilisateur = $this->Utilisateur->find($utilisateur_id);
            $role = $this->Utilisateur->getRoleForUser($utilisateur_id);
            $avis = $this->Utilisateur->getAvisForUser($utilisateur_id);
            $voitures = $this->Utilisateur->getVoituresForUser($utilisateur_id);
            $covoiturages = $this->Utilisateur->getCovoituragesForUser($utilisateur_id);
            $reservations = $this->Utilisateur->findParticipations($utilisateur_id);

            $this->render('utilisateurs.profile.index', compact('utilisateur', 'role', 'avis', 'voitures', 'covoiturages', 'reservations', 'auth_type'));
        }
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
    /**
     * Gère l'inscription d'un nouveau visiteur
     */
    public function inscrir()
    {
        $message = '';
        $message_type = '';
        $data = [];

        if (!empty($_POST)) {
            $pseudo = htmlspecialchars(trim($_POST['username']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = trim($_POST['password']);
            
            // Simuler data pour pré-remplir le form en cas d'échec
            $data = ['username' => $pseudo, 'email' => $email];

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
                 file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - Tentative création visiteur ($pseudo)\n", FILE_APPEND);
                 
                 if ($this->Utilisateur->createVisiteur($pseudo, $email, $password)) {
                     file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - Visiteur créé avec succès\n", FILE_APPEND);
                     
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
     * Vérification AJAX de l'unicité
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
            echo json_encode(['error' => 'Invalid field']);
        }
        exit;
    }
    
    /**
     * Valide l'email du visiteur connecté (passe statut_mail_id à 2)
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
     * Finalise l'inscription (Visiteur -> Utilisateur)
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
            // TODO: Ajouter validation robuste
            
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
                 
                 // On récupère le nouvel ID utilisateur grâce au pseudo/password stockés
                 $user = $this->Utilisateur->getUserByCredentials($visiteur->pseudo, $visiteur->password);
                 if ($user) {
                      $_SESSION['auth'] = $user->utilisateur_id;
                 }
                 
                 header('Location: index.php?p=utilisateurs.index');
                 exit;
            } else {
                // Erreur
            }
        }
    }
    /**
     * Envoie l'email de validation
     * @param string $email
     * @param string $pseudo
     * @return bool
     */
    private function sendValidationEmail($email, $pseudo)
    {
        file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - START sendValidationEmail($email)\n", FILE_APPEND);
        
        $mail = new PHPMailer(true);

        try {
            // Configuration serveur 
            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = function($str, $level) {
                file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). "\t" . $str . "\n", FILE_APPEND);
            };

            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = '463794f0298096'; 
            $mail->Password   = '885b66ca80971b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;
            $mail->CharSet    = 'UTF-8';

            // Destinataires
            $mail->setFrom('no-reply@ecoride.fr', 'EcoRide');
            $mail->addAddress($email, $pseudo);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue sur EcoRide - Confirmez votre email';
            $mail->Body    = "
                <h1>Bienvenue $pseudo !</h1>
                <p>Merci de vous être inscrit sur EcoRide.</p>
                <p>Pour finaliser votre inscription, veuillez confirmer votre voir email en cliquant sur le lien suivant (fictif pour le moment) :</p>
                <p><a href='#'>Confirmer mon email</a></p>
                <br>
                <p>L'équipe EcoRide</p>
            ";
            $mail->AltBody = "Bienvenue $pseudo ! Merci de confirmer votre email.";

            $mail->send();
            file_put_contents(ROOT . '/log/email_debug.txt', date('Y-m-d H:i:s'). " - SUCCESS sendValidationEmail\n", FILE_APPEND);
            return true;
        } catch (\Throwable $e) {
            file_put_contents(ROOT . '/log/email_debug.txt', "MAIL ERROR: " . $e->getMessage() . "\n" . $mail->ErrorInfo . "\n", FILE_APPEND);
            return false;
        }
    }
    /**
     * Modifie les informations du profil utilisateur
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
            $is_chauffeur = isset($_POST['role_chauffeur']);

            $role_id = 2; // Default fallback
            
            if ($is_chauffeur) {
                $role_id = 3; // Si Chauffeur est coché, on passe en "Chauffeur-Passager" (3)
            } elseif ($is_passager) {
                $role_id = 2; // Passager uniquement
            } else {
                // If nothing checked, default to Passager (2)
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

            // Update User info
            if ($this->Utilisateur->update($id, $data)) {
                $message = "Profil mis à jour avec succès.";
                $message_type = "success";
                
                // Update Preferences
                $this->Utilisateur->clearPreferences($id);
                
                // Handle standard checkboxes (fumeur, animaux)
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
                
                // Handle custom preferences
                if (!empty($_POST['custom_prefs'])) {
                    $customs = explode(',', $_POST['custom_prefs']);
                    foreach ($customs as $pref) {
                        $pref = trim($pref);
                        if (!empty($pref)) {
                            $this->Utilisateur->addPreference($id, $pref);
                        }
                    }
                }
                
                // Refresh data
                $utilisateur = $this->Utilisateur->find($id);

            } else {
                $message = "Erreur lors de la mise à jour.";
                $message_type = "error";
            }
        }

        $preferences = $this->Utilisateur->getPreferences($id);
        
        // Prepare preferences for view (simple array of labels)
        $user_prefs = [];
        foreach ($preferences as $p) {
            $user_prefs[] = $p->libelle;
        }

        $voitures = $this->Utilisateur->getVoituresForUser($id);
        $has_cars = !empty($voitures);

        $this->render('utilisateurs.profile.edit', compact('utilisateur', 'user_prefs', 'message', 'message_type', 'has_cars'));
    }
}
