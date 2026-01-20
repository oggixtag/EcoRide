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
        $user = $this->db->prepare("SELECT * FROM utilisateur WHERE pseudo = ? and password=?", [$username, $password], null, true);

        if ($user) {
            $_SESSION['auth'] = $user->utilisateur_id;
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
}
