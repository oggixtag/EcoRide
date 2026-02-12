<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des utilisateurs.
 * Fournit les méthodes d'authentification, gestion de profil, crédits, préférences et historique.
 */
class UtilisateurModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'utilisateur';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'utilisateur_id';

    /**
     * Récupère un utilisateur par son pseudo et mot de passe
     * @param string $pseudo
     * @param string $password
     * @return \NsAppEcoride\Entity\UtilisateurEntity|null
     */
    public function getUserByCredentials($pseudo, $password)
    {
        return $this->query(
            "SELECT * FROM utilisateur WHERE pseudo = ? AND password = ?",
            [$pseudo, $password],
            true
        );
    }

    /**
     * Récupère le rôle d'un utilisateur par son ID
     * @param int $utilisateur_id
     * @return string|null
     */
    public function getRoleForUser($utilisateur_id)
    {
        $result = $this->query(
            "SELECT r.libelle 
            FROM utilisateur u 
            JOIN role r ON u.role_id = r.role_id 
            WHERE u.utilisateur_id = ?",
            [$utilisateur_id],
            true
        );
        return $result ? $result->libelle : null;
    }

    /**
     * Récupère les avis d'un utilisateur par son ID
     * @param int $utilisateur_id
     * @return array
     */
    public function getAvisForUser($utilisateur_id)
    {
        return $this->query(
            "SELECT a.*, n.libelle as note, sa.libelle as statut 
            FROM avis a 
            JOIN statut_avis sa ON a.statut_avis_id = sa.statut_avis_id
            JOIN note n ON a.note_id = n.note_id
            WHERE a.utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Récupère les voitures d'un utilisateur par son ID
     * @param int $utilisateur_id
     * @return array
     */
    public function getVoituresForUser($utilisateur_id)
    {
        return $this->query(
            "SELECT 
                v.voiture_id,
                m.libelle AS marque,
                v.modele,
                v.immatriculation,
                v.energie,
                v.couleur,
                v.date_premiere_immatriculation AS annee
            FROM voiture v
            JOIN marque m ON v.marque_id = m.marque_id
            WHERE v.utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Récupère les covoiturages d'un utilisateur par son ID
     * @param int $utilisateur_id
     * @return array
     */
    public function getCovoituragesForUser($utilisateur_id)
    {
        return $this->query(
            "SELECT c.*, s.libelle as statut
            FROM covoiturage c
            JOIN voiture v ON c.voiture_id = v.voiture_id
            JOIN statut_covoiturage s ON c.statut_covoiturage_id = s.statut_covoiturage_id
            WHERE v.utilisateur_id = ?
            ORDER BY c.date_depart DESC, c.lieu_depart",
            [$utilisateur_id]
        );
    }

    /**
     * Déduit des crédits de l'utilisateur
     * @param int $utilisateur_id
     * @param int $montant_credit
     * @return bool
     */
    public function deduireCredit($utilisateur_id, $montant_credit)
    {
        // Vérifier que le montant est positif
        if ($montant_credit <= 0) {
            return false;
        }

        // Exécuter la mise à jour
        $rowCount = $this->db->executeRowCount(
            "UPDATE utilisateur 
            SET credit = credit - ? 
            WHERE utilisateur_id = ? AND credit >= ?",
            [$montant_credit, $utilisateur_id, $montant_credit]
        );

        // Retourner true si au moins une ligne a été affectée
        return $rowCount > 0;
    }

    /**
     * Ajoute des crédits à l'utilisateur
     * @param int $utilisateur_id
     * @param int $montant_credit
     * @return bool
     */
    public function crediter($utilisateur_id, $montant_credit)
    {
        // Vérifier que le montant est positif
        if ($montant_credit <= 0) {
            return false;
        }

        // Exécuter la mise à jour
        $rowCount = $this->db->executeRowCount(
            "UPDATE utilisateur 
            SET credit = credit + ? 
            WHERE utilisateur_id = ?",
            [$montant_credit, $utilisateur_id]
        );

        return $rowCount > 0;
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return object|null
     */
    public function findByEmail($email)
    {
        return $this->query(
            "SELECT * FROM utilisateur WHERE email = ?",
            [$email],
            true
        );
    }

    /**
     * Récupère un utilisateur par son pseudo
     * @param string $pseudo
     * @return object|null
     */
    public function findByPseudo($pseudo)
    {
        return $this->query(
            "SELECT * FROM utilisateur WHERE pseudo = ?",
            [$pseudo],
            true
        );
    }

    /**
     * Récupère les participations (réservations) d'un utilisateur
     * @param int $utilisateur_id
     * @return array
     */
    public function findParticipations($utilisateur_id)
    {
        return $this->query(
            "SELECT 
                p.utilisateur_id,
                p.covoiturage_id,
                c.date_depart,
                c.heure_depart,
                c.lieu_depart,
                c.lieu_arrivee,
                c.prix_personne,
                s.libelle as statut,
                u.pseudo
            FROM participe p
            JOIN covoiturage c ON p.covoiturage_id = c.covoiturage_id
            JOIN voiture v ON c.voiture_id = v.voiture_id
            JOIN utilisateur u ON v.utilisateur_id = u.utilisateur_id
            JOIN statut_covoiturage s ON c.statut_covoiturage_id = s.statut_covoiturage_id
            WHERE p.utilisateur_id = ?
            ORDER BY c.date_depart DESC",
            [$utilisateur_id]
        );
    }
    /**
     * Crée un nouveau visiteur (pré-inscription)
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createVisiteur($pseudo, $email, $password)
    {
        return $this->query(
            "INSERT INTO visiteur_utilisateur (pseudo, email, password, statut_mail_id) VALUES (?, ?, ?, 1)",
            [$pseudo, $email, $password]
        );
    }

    /**
     * Vérifie si un pseudo est unique (dans utilisateur et visiteur_utilisateur)
     * @param string $pseudo
     * @return bool
     */
    public function isPseudoUnique($pseudo)
    {
        // Vérifier dans la table utilisateur
        $user = $this->query("SELECT COUNT(*) as count FROM utilisateur WHERE pseudo = ?", [$pseudo], true);
        if ($user && $user->count > 0) return false;

        // Vérifier dans la table visiteur_utilisateur
        $visiteur = $this->query("SELECT COUNT(*) as count FROM visiteur_utilisateur WHERE pseudo = ?", [$pseudo], true);
        if ($visiteur && $visiteur->count > 0) return false;

        return true;
    }

    /**
     * Vérifie si un email est unique (dans utilisateur et visiteur_utilisateur)
     * @param string $email
     * @return bool
     */
    public function isEmailUnique($email)
    {
        // Vérifier dans la table utilisateur
        $user = $this->query("SELECT COUNT(*) as count FROM utilisateur WHERE email = ?", [$email], true);
        if ($user && $user->count > 0) return false;

        // Vérifier dans la table visiteur_utilisateur
        $visiteur = $this->query("SELECT COUNT(*) as count FROM visiteur_utilisateur WHERE email = ?", [$email], true);
        if ($visiteur && $visiteur->count > 0) return false;

        return true;
    }

    /**
     * Récupère un visiteur par ID
     * @param int $id
     * @return object|null
     */
    public function getVisiteur($id)
    {
        return $this->query(
            "SELECT * FROM visiteur_utilisateur WHERE visiteur_utilisateur_id = ?",
            [$id],
            true
        );
    }
    
    /**
    * Met à jour le statut mail d'un visiteur
    * @param int $id
    * @param int $statut_id
    */
    public function updateVisiteurStatut($id, $statut_id) {
         return $this->query(
            "UPDATE visiteur_utilisateur SET statut_mail_id = ? WHERE visiteur_utilisateur_id = ?",
            [$statut_id, $id]
        );
    }
    
    /**
     * Transforme un visiteur en utilisateur
     * @param int $visiteur_id
     * @param array $data Données complémentaires (nom, prenom, etc.)
     * @return bool
     */
    public function upgradeVisiteurToUser($visiteur_id, $data) {
        $visiteur = $this->getVisiteur($visiteur_id);
        if (!$visiteur) return false;
        
        // 1. Insérer dans utilisateur
        // Note: credit a une valeur par défaut de 20 dans la DB
        $res = $this->query(
            "INSERT INTO utilisateur (
                nom, prenom, email, password, telephone, adresse, date_naissance, photo, pseudo, role_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['nom'],
                $data['prenom'],
                $visiteur->email,
                $visiteur->password,
                $data['telephone'],
                $data['adresse'],
                $data['date_naissance'],
                $data['photo'] ?? null,
                $visiteur->pseudo,
                $data['role_id'] ?? 2 // Rôle par défaut (ex: Passager/User standard - à confirmer selon la table role)
            ]
        );
        
        if ($res) {
            // 2. Supprimer de visiteur_utilisateur
            $this->query("DELETE FROM visiteur_utilisateur WHERE visiteur_utilisateur_id = ?", [$visiteur_id]);
            return true;
        }
        return false;
    }
    /**
     * Récupère les préférences d'un utilisateur
     * @param int $utilisateur_id
     * @return array
     */
    public function getPreferences($utilisateur_id)
    {
        return $this->query(
            "SELECT * FROM preference WHERE utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Ajoute une préférence pour un utilisateur
     * @param int $utilisateur_id
     * @param string $libelle
     * @return bool
     */
    public function addPreference($utilisateur_id, $libelle)
    {
        return $this->query(
            "INSERT INTO preference (libelle, utilisateur_id) VALUES (?, ?)",
            [$libelle, $utilisateur_id]
        );
    }

    /**
     * Supprime toutes les préférences d'un utilisateur (pour mise à jour)
     * @param int $utilisateur_id
     * @return bool
     */
    public function clearPreferences($utilisateur_id)
    {
        return $this->query(
            "DELETE FROM preference WHERE utilisateur_id = ?",
            [$utilisateur_id]
        );
    }

    /**
     * Met à jour les informations d'un utilisateur
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $fields)
    {
        $sql_parts = [];
        $attributes = [];

        foreach ($fields as $k => $v) {
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }

        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);

        return $this->query(
            "UPDATE {$this->table} SET $sql_part WHERE {$this->column} = ?",
            $attributes
        );
    }
    /**
     * Récupère l'historique des covoiturages (en tant que chauffeur)
     * Strictement réservé aux chauffeurs ou chauffeurs-passagers
     * @param int $utilisateur_id
     * @return array
     */
    public function getHistoriqueCovoiturages($utilisateur_id)
    {
        // Vérification du rôle
        $role = $this->getRoleForUser($utilisateur_id);
        if ($role !== 'Chauffeur' && $role !== 'Chauffeur-Passager') {
             return [];
        }

        return $this->query(
            "SELECT c.*, s.libelle as statut
            FROM covoiturage c
            JOIN voiture v ON c.voiture_id = v.voiture_id
            JOIN statut_covoiturage s ON c.statut_covoiturage_id = s.statut_covoiturage_id
            WHERE v.utilisateur_id = ? 
            AND c.date_depart < CURRENT_DATE()
            ORDER BY c.date_depart DESC, c.heure_depart DESC",
            [$utilisateur_id]
        );
    }

    /**
     * Récupère l'historique des participations (en tant que passager)
     * Strictement réservé aux passagers (ou Chauffeur-Passagers ? Non, consigne US: "considérer son statut 'passager'")
     * Mais un "Chauffeur-Passager" EST un passager aussi.
     * Pour la consigne stricte:
     * "1.2 pour la section `Mes Réservations ...` Il faut aussi considérer son statut « passager »"
     * Dans l'esprit US, si je suis Chauffeur-Passager, je peux avoir des réservations ? Oui.
     * Mais si je suis JUSTE 'Chauffeur', j'ai pas accès à cette section ?
     * Code existant profile/index.php (L84): $utilisateur->role_id == 3 => 'Chauffeur-Passager'.
     * Block résas (L205) s'affiche pour tout le monde si !empty($reservations).
     * Mais le bouton historique doit être conditionné.
     * Je vais inclure 'Passager' et 'Chauffeur-Passager' pour cette méthode, car techniquement ils sont passagers.
     * UPDATE: L'utilisateur a dit: "It is also necessary to consider their status as « passager »."
     * Je vais vérifier si le role est 'Passager' ou 'Chauffeur-Passager'. S'il est juste 'Chauffeur', il ne devrait pas avoir de réservations théoriquement (sauf s'il a changé de rôle).
     */
    public function getHistoriqueParticipations($utilisateur_id)
    {
        // Vérification du rôle
        $role = $this->getRoleForUser($utilisateur_id);
        // Si le rôle n'est ni Passager ni Chauffeur-Passager, on ne retourne rien (ex: Chauffeur pur, Admin...)
        // Note: La chaine exacte retournée par getRoleForUser dépend de la DB.
        // D'après profile/index.php, on affiche 'Passager', 'Chauffeur', 'Chauffeur-Passager'.
        // Mais checkons la table `role` dans DDL.sql... on n'a que la structure.
        // Supposons que les libellés soient 'Passager', 'Chauffeur', 'Chauffeur-Passager'.
        
        $allowed_roles = ['Passager', 'Chauffeur-Passager'];
        // Si c'est juste 'Chauffeur', on refuse ? 
        // US Logique: "Considérer son statut Passager". Un pur chauffeur n'est pas passager.
        
        if (!in_array($role, $allowed_roles)) {
             return [];
        }

        return $this->query(
            "SELECT 
                p.utilisateur_id,
                p.covoiturage_id,
                c.date_depart,
                c.heure_depart,
                c.lieu_depart,
                c.lieu_arrivee,
                c.prix_personne,
                s.libelle as statut,
                u.pseudo
            FROM participe p
            JOIN covoiturage c ON p.covoiturage_id = c.covoiturage_id
            JOIN voiture v ON c.voiture_id = v.voiture_id
            JOIN utilisateur u ON v.utilisateur_id = u.utilisateur_id
            JOIN statut_covoiturage s ON c.statut_covoiturage_id = s.statut_covoiturage_id
            WHERE p.utilisateur_id = ?
            AND c.date_depart < CURRENT_DATE()
            ORDER BY c.date_depart DESC",
            [$utilisateur_id]
        );
    }
    /**
     * Suspendre un utilisateur
     * @param int $id
     * @return bool
     */
    public function suspendre($id)
    {
        return $this->query("UPDATE utilisateur SET est_suspendu = 1 WHERE utilisateur_id = ?", [$id]);
    }

    /**
     * Réactiver un utilisateur
     * @param int $id
     * @return bool
     */
    public function reactiver($id)
    {
        return $this->query("UPDATE utilisateur SET est_suspendu = 0 WHERE utilisateur_id = ?", [$id]);
    }
    /**
     * Récupère tous les utilisateurs avec le libellé de leur rôle
     * @return array
     */
    public function findAllWithRole()
    {
        return $this->query(
            "SELECT u.*, r.libelle as role_libelle 
            FROM utilisateur u 
            LEFT JOIN role r ON u.role_id = r.role_id
            ORDER BY u.prenom"
        );
    }
}
