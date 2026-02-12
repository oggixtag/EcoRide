<?php

namespace NsCoreEcoride\Auth;

use NsCoreEcoride\Database\Database;
use \App;

class DbAuth
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    
    /**
     * @param string $username
     * @param string $password
     * @param string $table 
     * @return boolean 
     * */
    public function login($username, $password)
    {
        // 1. Essayer de se connecter en tant qu'utilisateur complet
        $user = $this->db->prepare("SELECT * FROM utilisateur WHERE pseudo = ? and password=?", [$username, $password], null, true);

        if ($user) {
            $_SESSION['auth'] = $user->utilisateur_id;
            $_SESSION['auth_type'] = 'utilisateur';
            return true;
        }

        // 2. Essayer de se connecter en tant que visiteur (inscription en cours)
        $visiteur = $this->db->prepare("SELECT * FROM visiteur_utilisateur WHERE pseudo = ? and password=?", [$username, $password], null, true);

        if ($visiteur) {
            $_SESSION['auth'] = $visiteur->visiteur_utilisateur_id;
            $_SESSION['auth_type'] = 'visiteur';
            return true;
        }

        return false;
    }

    /**
     * @param string $username
     * @param string $password
     * @return int|false Retourne l'id_poste (1=admin, 2=employe) ou false si échec
     * */
    public function loginEmploye($username, $password)
    {
        // Récupérer l'employé avec son poste
        $employe = $this->db->prepare("SELECT * FROM employe WHERE pseudo = ? and password=?", [$username, $password], null, true);

        if ($employe) {
            // Stocker l'ID dans la session correspondante selon le poste
            if ($employe->id_poste == 1) {
                // Administrateur - stocker l'ID dans auth_admin
                $_SESSION['auth_admin'] = $employe->id_emp;
            } else {
                // Employé - stocker l'ID dans auth_employe
                $_SESSION['auth_employe'] = $employe->id_emp;
            }
            
            // Retourner le poste pour que le contrôleur puisse rediriger correctement
            return $employe->id_poste;
        }

        return false;
    }

    /**
     * Vérifie si un utilisateur est connecté
     * @return bool
     */
    public function isConnected()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['auth']) && !empty($_SESSION['auth']);
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     * @return int|null
     */
    public function getConnectedUserId()
    {
        return $_SESSION['auth'] ?? null;
    }

    public function getAuthType()
    {
        return $_SESSION['auth_type'] ?? null;
    }

    /**
     * Vérifie si un employé est connecté
     * @return bool
     */
    public function isEmploye()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['auth_employe']) && !empty($_SESSION['auth_employe']);
    }
    /**
     * Récupère l'ID de l'employé connecté
     * @return int|null
     */
    public function getEmployeId()
    {
        return $_SESSION['auth_employe'] ?? null;
    }

    /**
     * Vérifie si un administrateur est connecté
     * @return bool
     */
    public function isAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['auth_admin']) && !empty($_SESSION['auth_admin']);
    }

    /**
     * Récupère l'ID de l'administrateur connecté
     * @return int|null
     */
    public function getAdminId()
    {
        return $_SESSION['auth_admin'] ?? null;
    }
}
