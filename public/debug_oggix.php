<?php
define('ROOT', dirname(__DIR__));
require(ROOT . '/app/App.php');
App::load();

$app = App::getInstance();
$db = $app->getDb();

$pseudo = 'oggix';
$user = $db->prepare("SELECT * FROM utilisateur WHERE pseudo = ?", [$pseudo], null, true);

if (!$user) {
    echo "User '$pseudo' not found.\n";
    exit;
}

echo "User Found: ID={$user->utilisateur_id}, RoleID={$user->role_id}\n";
echo "Date du jour (PHP): " . date('Y-m-d H:i:s') . "\n";
$db_time = $db->query("SELECT NOW() as db_time", null, true);
echo "Date du jour (MySQL): " . $db_time->db_time . "\n\n";

// Check raw covoiturages (Driver)
echo "--- Raw Covoiturages (Driver) ---\n";
// Joining on voiture using id
$covoiturages = $db->prepare("SELECT c.*, v.utilisateur_id as driver_id FROM covoiturage c JOIN voiture v ON c.voiture_id = v.voiture_id WHERE v.utilisateur_id = ?", [$user->utilisateur_id], null, false);
if ($covoiturages) {
    foreach ($covoiturages as $c) {
        echo "ID: {$c->covoiturage_id}, Date: {$c->date_depart}, Heure: {$c->heure_depart}, StatutID: {$c->statut_covoiturage_id}\n";
    }
} else {
    echo "No trips found for driver.\n";
}

// Check raw participations (Passenger)
echo "\n--- Raw Participations (Passenger) ---\n";
$participations = $db->prepare("SELECT * FROM participe WHERE utilisateur_id = ?", [$user->utilisateur_id], null, false);
if ($participations) {
    foreach ($participations as $p) {
        $trip = $db->prepare("SELECT * FROM covoiturage WHERE covoiturage_id = ?", [$p->covoiturage_id], null, true);
        if ($trip) {
             echo "TripID: {$p->covoiturage_id}, Date: {$trip->date_depart}, Heure: {$trip->heure_depart}\n";
        } else {
             echo "TripID: {$p->covoiturage_id} (NOT FOUND)\n";
        }
    }
} else {
    echo "No participations found.\n";
}

echo "\n--- Roles ---\n";
$roles = $db->query("SELECT * FROM role");
foreach ($roles as $r) {
    echo "ID: {$r->role_id}, Libelle: {$r->libelle}\n";
}
$statuts = $db->query("SELECT * FROM statut_covoiturage");
// Test Model Method Directly
echo "\n--- Testing Model Method ---\n";
require_once 'core/Model/Model.php';
require_once 'app/Model/UtilisateurModel.php';

use NsAppEcoride\Model\UtilisateurModel;

$model = new UtilisateurModel($db);
$history = $model->getHistoriqueCovoiturages($user->utilisateur_id);

echo "Model returned " . count($history) . " items.\n";
foreach ($history as $h) {
    echo "Trip ID: {$h->covoiturage_id}\n";
}
