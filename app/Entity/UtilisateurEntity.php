<?php

namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

class UtilisateurEntity extends Entity
{
    public function __construct() {}

    public function getUrl()
    {
        return 'index.php?p=utilisateur&id=' . $this->utilisateur_id;
    }

    /*public function getAvatar()
    {
        return 'public/images/avatars/' . $this->avatar;
    }*/

    public function getFullName()
    {
        return htmlspecialchars($this->prenom . ' ' . $this->nom);
    }

    public function getExtrait()
    {
        $html = '<a href="' . $this->getUrl() . '">Voir le profil</a>';
        return $html;
    }

    public function getPseudo()
    {
        return htmlspecialchars($this->pseudo);
    }


    /**
     * Retourne le rôle de l'utilisateur
     * @return string
     */
    public function getRole()
    {
        return htmlspecialchars($this->role);
    }

    /**
     * Retourne l'email de l'utilisateur
     * @return string
     */
    public function getEmail()
    {
        return htmlspecialchars($this->email);
    }
}
