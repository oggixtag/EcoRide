<?php
define('ROOT', __DIR__);
require __DIR__ . '/app/App.php';
App::load();
$db = App::getInstance()->getDb();
try {
    $rows = $db->query("SELECT * FROM statut_avis");
    echo "ID | Libelle\n";
    echo "---|--------\n";
    foreach ($rows as $row) {
        echo $row->statut_avis_id . " | " . $row->libelle . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
