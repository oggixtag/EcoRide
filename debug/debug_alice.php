<?php
define('ROOT', dirname(__DIR__));
require(ROOT . '/app/App.php');
App::load();

$db = App::getInstance()->getDb();
$model = new \NsAppEcoride\Model\UtilisateurModel($db);

// 1. Check Alice1 (ID 1)
$u = $model->find(1);
echo "User: " . $u->pseudo . " (ID: 1)\n";
$role = $model->getRoleForUser(1);
echo "Role: " . $role . "\n";

// 2. Check History Count
$hist = $model->getHistoriqueCovoiturages(1);
echo "History Count (Before): " . count($hist) . "\n";
foreach ($hist as $h) {
    echo " - Trip Date: " . $h->date_depart . "\n";
}

// 3. Insert Past Trip if empty
if (empty($hist)) {
    echo "Inserting past trip for verification...\n";
    $db->prepare("INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, statut_covoiturage_id, nb_place, prix_personne, voiture_id) 
                  VALUES ('2025-01-01', '10:00:00', 'Paris', '2025-01-01', '12:00:00', 'Lyon', 3, 3, 20, 1)", []);
    
    $hist = $model->getHistoriqueCovoiturages(1);
    echo "History Count (After): " . count($hist) . "\n";
} else {
    echo "Alice already has history.\n";
}
