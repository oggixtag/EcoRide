<?php
/**
 * Script de migration des mots de passe en clair vers des hashs bcrypt.
 * À exécuter une seule fois.
 */

define('ROOT', dirname(__DIR__));
require ROOT . '/app/App.php';
App::load();

$db = App::getInstance()->getDb();

echo "Début de la migration des mots de passe...\n";

// 1. Migrer la table 'utilisateur'
echo "Migration de la table 'utilisateur'...\n";
$users = $db->query("SELECT utilisateur_id, password FROM utilisateur");

$count = 0;
foreach ($users as $user) {
    // Si le mot de passe n'est pas déjà un hash (longueur < 60 pour bcrypt)
    if (strlen($user->password) < 60) {
        $new_hash = password_hash($user->password, PASSWORD_DEFAULT);
        $db->prepare(
            "UPDATE utilisateur SET password = ? WHERE utilisateur_id = ?",
            [$new_hash, $user->utilisateur_id]
        );
        $count++;
    }
}
echo "$count utilisateurs mis à jour.\n";

// 1b. Migrer la table 'visiteur_utilisateur'
echo "Migration de la table 'visiteur_utilisateur'...\n";
$visiteurs = $db->query("SELECT visiteur_utilisateur_id, password FROM visiteur_utilisateur");
$countVisiteur = 0;
foreach ($visiteurs as $v) {
    if (strlen($v->password) < 60) {
        $new_hash = password_hash($v->password, PASSWORD_DEFAULT);
        $db->prepare(
            "UPDATE visiteur_utilisateur SET password = ? WHERE visiteur_utilisateur_id = ?",
            [$new_hash, $v->visiteur_utilisateur_id]
        );
        $countVisiteur++;
    }
}
echo "$countVisiteur visiteurs mis à jour.\n";

// 2. Migrer la table 'employe' (si elle a des mots de passe)
echo "Migration de la table 'employe'...\n";
// Vérifier d'abord si la table a une colonne password (supposition basée sur DbAuth::loginEmploye)
try {
    $employes = $db->query("SELECT id_emp, password FROM employe");
    $countEmp = 0;
    foreach ($employes as $emp) {
        if (strlen($emp->password) < 60) {
            $new_hash = password_hash($emp->password, PASSWORD_DEFAULT);
            $db->prepare(
                "UPDATE employe SET password = ? WHERE id_emp = ?",
                [$new_hash, $emp->id_emp]
            );
            $countEmp++;
        }
    }
    echo "$countEmp employés mis à jour.\n";
} catch (PDOException $e) {
    echo "Erreur ou table 'employe' sans mot de passe : " . $e->getMessage() . "\n";
}

echo "Migration terminée.\n";
