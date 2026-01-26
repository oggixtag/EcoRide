<?php
// Fix for the LOCAL test database used by PHPUnit
define('ROOT', __DIR__);
require __DIR__ . '/core/Autoloader.php';
NsCoreEcoride\Autoloader::register();

use NsCoreEcoride\Database\MysqlDatabase;

$db = new MysqlDatabase('abcrdv_ecoride_db', 'root', '', 'localhost');

try {
    $stmt = $db->query("SELECT * FROM statut_avis WHERE libelle = 'refusé'", null, true);
    if (!$stmt) {
        $db->query("INSERT INTO statut_avis (libelle) VALUES ('refusé')");
        echo "Inserted 'refusé' status into LOCAL test DB.\n";
    } else {
        echo "Status 'refusé' already exists in LOCAL test DB.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
