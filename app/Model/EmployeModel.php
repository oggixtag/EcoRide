<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour la gestion des employés.
 * Fournit les méthodes CRUD, suspension/réactivation et statistiques.
 */
class EmployeModel extends Model
{
    /** @var string Nom de la table en base de données */
    protected $table = 'employe';
    
    /** @var string Nom de la colonne clé primaire */
    protected $column = 'id_emp';

    /**
     * Suspendre un employé
     * @param int $id
     * @return bool
     */
    public function suspendre($id)
    {
        return $this->query("UPDATE employe SET est_suspendu = 1 WHERE id_emp = ?", [$id]);
    }

    /**
     * Réactiver un employé
     * @param int $id
     * @return bool
     */
    public function reactiver($id)
    {
        return $this->query("UPDATE employe SET est_suspendu = 0 WHERE id_emp = ?", [$id]);
    }

    /**
     * Récupère tous les employés sauf l'administrateur
     * @return array
     */
    public function findAllWithoutAdmin()
    {
        return $this->query(
            "SELECT e.*, p.intitule as poste_libelle 
            FROM employe e 
            JOIN poste p ON p.id_poste = e.id_poste 
            WHERE p.intitule <> 'Administrateur'"
        );
    }

    /**
     * Récupérer le nombre de covoiturages par jour
     * @return array
     */
    public function recupererCovoituragesParJour()
    {
        return $this->query("
            SELECT DATE(date_depart) as date, COUNT(*) as total 
            FROM covoiturage 
            GROUP BY DATE(date_depart) 
            ORDER BY DATE(date_depart) ASC
            LIMIT 30"
        );
    }

    /**
     * Récupérer les crédits gagnés par la plateforme par jour
     * Note: On assume que la plateforme gagne 2 crédits par covoiturage créé.
     * @return array
     */
    public function recupererCreditsParJour()
    {
        return $this->query("
            SELECT DATE(date_depart) as date, COUNT(*) * 2 as total_credits 
            FROM covoiturage 
            GROUP BY DATE(date_depart) 
            ORDER BY DATE(date_depart) ASC
            LIMIT 30"
        );
    }

    /**
     * Obtenir le total des crédits gagnés par la plateforme
     * @return int
     */
    public function obtenirTotalCredits()
    {
        $result = $this->query("SELECT COUNT(*) * 2 as total FROM covoiturage", null, true);
        return $result ? $result->total : 0;
    }

    /**
     * Récupère la liste des employés formatée pour un select (id => Nom Prénom)
     * @return array
     */
    public function getManagersList()
    {
        $employes = $this->query(
            "SELECT e.id_emp, e.nom, e.prenom 
             FROM employe e
             JOIN poste p ON e.id_poste = p.id_poste
             WHERE p.intitule = 'Administrateur'"
        );
        $list = [];
        foreach ($employes as $emp) {
            $list[$emp->id_emp] = $emp->nom . ' ' . $emp->prenom;
        }
        return $list;
    }
}
