<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

class UtilisateurModel extends Model
{
    protected $table = 'utilisateur';
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
        $result = $this->query(
            "UPDATE utilisateur 
            SET credit = credit - ? 
            WHERE utilisateur_id = ? AND credit >= ?",
            [$montant_credit, $utilisateur_id, $montant_credit]
        );

        // Retourner true si au moins une ligne a été affectée
        return $result > 0;
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
}
