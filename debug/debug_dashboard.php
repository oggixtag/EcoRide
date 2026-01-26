<?php
// Simulate full request context
define('ROOT', __DIR__);
require __DIR__ . '/app/App.php';
App::load();

// Mock Session
$_SESSION['auth_employe'] = 2; // Sophie

// buffer output
ob_start();

use NsAppEcoride\Controller\Admin\EmployesController;
$controller = new EmployesController();

// We need to prevent exit() from killing the script if it redirects
// But dashboard() only exits if isEmploye() is false.
// If isEmploye() works, it renders the view.

try {
    $controller->dashboard();
    $output = ob_get_clean();
    
    if (strpos($output, 'Tableau de bord - Employé') !== false) {
        echo "Dashboard rendered successfully.\n";
    } else {
        echo "Dashboard rendered but content missing or unexpected.\n";
        echo substr($output, 0, 200) . "...\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
