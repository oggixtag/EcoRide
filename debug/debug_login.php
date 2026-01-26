<?php
define('ROOT', __DIR__);
require __DIR__ . '/app/App.php';
App::load();

use NsCoreEcoride\Auth\DbAuth;

// 1. Simulate Login
$auth = new DbAuth(App::getInstance()->getDb());
echo "Attempting login for sophie.durand@mail.com...\n";
$success = $auth->loginEmploye('sophie.durand@mail.com', 'pwd_sophie');

if ($success) {
    echo "Login successful.\n";
    echo "Session auth_employe: " . ($_SESSION['auth_employe'] ?? 'NOT SET') . "\n";
    
    // 2. Check isEmploye
    $isEmp = $auth->isEmploye();
    echo "isEmploye() returns: " . ($isEmp ? 'TRUE' : 'FALSE') . "\n";
    
    // 3. Check Session Persistence (simulated in same script execution)
    if (isset($_SESSION['auth_employe']) && $_SESSION['auth_employe'] == 2) { // 2 is Sophie's ID from DML
         echo "Session check PASSED.\n";
    } else {
         echo "Session check FAILED. ID expected 2.\n";
    }

} else {
    echo "Login failed.\n";
}
