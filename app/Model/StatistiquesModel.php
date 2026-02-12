<?php

namespace NsAppEcoride\Model;

use NsCoreEcoride\Model\Model;

/**
 * Modèle pour les statistiques de la plateforme.
 * Fournit les méthodes de récupération des données statistiques pour le tableau de bord admin.
 */
class StatistiquesModel extends Model
{
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
            ORDER BY DATE(date_depart) DESC
            LIMIT 30"
        );
    }

    /**
     * Récupérer les crédits gagnés par la plateforme par jour
     * Note: On assume que la plateforme gagne 2 crédits par covoiturage créé (US9).
     * @return array
     */
    public function recupererCreditsParJour()
    {
        // Si la plateforme gagne 2 crédits par covoiturage créé:
        return $this->query("
            SELECT DATE(date_depart) as date, COUNT(*) * 2 as total_credits 
            FROM covoiturage 
            GROUP BY DATE(date_depart) 
            ORDER BY DATE(date_depart) DESC
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
}
