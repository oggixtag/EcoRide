<?php
define('ROOT', dirname(__DIR__));
require ROOT . '/app/App.php';
App::load();

// Mock Session
$_SESSION['auth'] = 6; // User 'Frank6' (Passager)

// Mock Request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['covoiturage_id'] = 1; // Valid ID

// Capture output
ob_start();
try {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->participer();
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage();
}
$output = ob_get_clean();

echo "START" . $output . "END";
