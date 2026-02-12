<?php
define('ROOT', dirname(__DIR__));
require 'app/App.php';
App::load();
$db = App::getInstance()->getDb();

/**
 * Script de génération de données de test (Seeding).
 * Remplit la table 'covoiturage' avec des données fictives pour tester les graphiques du dashboard admin.
 * 
 * Usage : php tests/seed_covoiturages.php
 */

echo "Génération des covoiturages (Seeding)...\n";

// S'assurer que nous avons un utilisateur et une voiture
$db->query("INSERT IGNORE INTO role (role_id, libelle) VALUES (1, 'VitreTeinte'), (2, 'Utilisateur'), (3, 'Administrateur')");
$db->query("INSERT IGNORE INTO utilisateur (utilisateur_id, nom, prenom, email, password, pseudo, role_id) VALUES (999, 'Seed', 'User', 'seed@test.com', 'pass', 'Seeder', 2)");
$db->query("INSERT IGNORE INTO marque (marque_id, libelle) VALUES (1, 'Peugeot')");
$db->query("INSERT IGNORE INTO voiture (voiture_id, modele, immatriculation, energie, marque_id, utilisateur_id) VALUES (999, '208', 'SEED-999', 'Essence', 1, 999)");
$db->query("INSERT IGNORE INTO statut_covoiturage (statut_covoiturage_id, libelle) VALUES (1, 'en attente'), (2, 'validé'), (3, 'terminé')");

// Insérer des covoiturages pour les 10 derniers jours
for ($i = 0; $i < 10; $i++) {
    $date = date('Y-m-d', strtotime("-$i days"));
    // Insérer 1 à 5 trajets par jour
    $nb = rand(1, 5);
    for ($j = 0; $j < $nb; $j++) {
        $db->prepare("INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) VALUES (?, '08:00:00', 'Paris', 'Lyon', 3, 4, 20, 999)", [$date]);
    }
}

echo "Terminé.\n";
