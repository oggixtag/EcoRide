<?php

namespace NsAppEcoride\Controller\Admin;

use NsCoreEcoride\Controller\Controller; // Ou AppController ?
// Vérifier le namespace AppController. Habituellement NsAppEcoride\Controller\AppController ?
// Mais EmployesController étend AppController. Utilisons cela.

/**
 * Contrôleur pour les fonctionnalités d'administration.
 * Gère le tableau de bord admin, la gestion des utilisateurs et la suspension/réactivation.
 */
class AdministrateurController extends AppController
{
    /**
     * Constructeur du contrôleur administrateur.
     * Note: pas de vérification dans le constructeur pour permettre logout().
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // Note: pas de vérification dans le constructeur pour permettre logout()
    }

    /**
     * Vérifie si l'utilisateur actuel est un administrateur
     * @return bool
     */
    private function isAdmin()
    {
        $app = \App::getInstance();
        $auth = new \NsCoreEcoride\Auth\DbAuth($app->getDb());
        return $auth->isAdmin();
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     * Contient les statistiques des covoiturages et des crédits.
     * 
     * @return void Affiche la vue admin.dashboard
     */
    public function dashboard()
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }
        
        $this->loadModel('Employe');
        
        $covoiturages = $this->Employe->recupererCovoituragesParJour();
        $credits_par_jour = $this->Employe->recupererCreditsParJour();
        $credits_total = $this->Employe->obtenirTotalCredits();
        
        $this->render('admin.dashboard', compact('covoiturages', 'credits_par_jour', 'credits_total'));
    }

    /**
     * Affiche la liste de tous les utilisateurs avec leurs rôles.
     * Permet de gérer la suspension/réactivation des comptes.
     * 
     * @return void Affiche la vue admin.index
     */
    public function utilisateurs()
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }
        
        $this->loadModel('Utilisateur');
        $utilisateurs = $this->Utilisateur->findAllWithRole();
        $this->render('admin.index', compact('utilisateurs'));
    }

    /**
     * Suspend un compte utilisateur.
     * Envoie un email de notification à l'utilisateur concerné.
     * 
     * @return void Redirige vers la liste des utilisateurs
     */
    public function suspendreUtilisateur()
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }
        
        if (!empty($_POST['id'])) {
            $this->loadModel('Utilisateur');
            $id = $_POST['id'];

            // Récupérer l'utilisateur pour l'email
            $utilisateur = $this->Utilisateur->find($id);
            
            if ($utilisateur) {
                // Suspendre
                $this->Utilisateur->suspendre($id);
                
                // Envoyer Email
                $mailer = new \NsAppEcoride\Service\Mailer();
                $subject = "Suspension de votre compte EcoRide";
                $body = "
                    <h1>Compte Suspendu</h1>
                    <p>Bonjour " . htmlspecialchars($utilisateur->pseudo) . ",</p>
                    <p>Votre compte EcoRide a été suspendu par un administrateur suite au non-respect de nos conditions d'utilisation ou à des signalements répétés.</p>
                    <p>Pour toute réclamation, veuillez contacter le support.</p>
                    <p>L'équipe EcoRide.</p>
                ";
                $mailer->send($utilisateur->email, $subject, $body);
            }
        }
        header('Location: index.php?p=admin.index'); 
    }

    /**
     * Réactive un compte utilisateur précédemment suspendu.
     * Envoie un email de notification à l'utilisateur concerné.
     * 
     * @return void Redirige vers la liste des utilisateurs
     */
    public function reactiverUtilisateur()
    {
        if (!$this->isAdmin()) {
            $this->forbidden();
        }
        
        if (!empty($_POST['id'])) {
            $this->loadModel('Utilisateur');
            $id = $_POST['id'];

            // Récupérer l'utilisateur pour l'email
            $utilisateur = $this->Utilisateur->find($id);

            if ($utilisateur) {
                // Réactiver
                $this->Utilisateur->reactiver($id);
                
                // Envoyer Email
                $mailer = new \NsAppEcoride\Service\Mailer();
                $subject = "Réactivation de votre compte EcoRide";
                $body = "
                    <h1>Compte Réactivé</h1>
                    <p>Bonjour " . htmlspecialchars($utilisateur->pseudo) . ",</p>
                    <p>Nous avons le plaisir de vous informer que votre compte EcoRide a été réactivé.</p>
                    <p>Vous pouvez à nouveau vous connecter et utiliser tous nos services.</p>
                    <p>À bientôt sur EcoRide !</p>
                    <p>L'équipe EcoRide.</p>
                ";
                $mailer->send($utilisateur->email, $subject, $body);
            }
        }
        header('Location: index.php?p=admin.index');
    }

    /**
     * Déconnecte l'administrateur.
     * Efface seulement auth_admin sans toucher à auth_employe.
     * 
     * @return void Redirige vers la page de connexion employé
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Effacer seulement la session admin, ne pas toucher à auth_employe
        unset($_SESSION['auth_admin']);
        header('Location: index.php?p=admin.employes.login');
        exit;
    }
}
