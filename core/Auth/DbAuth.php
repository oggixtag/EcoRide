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
     * Vérifie si un utilisateur est connecté
     * @return bool
     */
    public function isConnected()
    {
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

    /**
     * Récupère le type d'utilisateur connecté ('utilisateur' ou 'visiteur')
     * @return string|null
     */
    public function getAuthType()
    {
        return $_SESSION['auth_type'] ?? null;
    }
}
