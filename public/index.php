<?php
define('ROOT', dirname(__DIR__));
require(ROOT . '/app/App.php');
App::load();

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'home';
}

// naming : controller + route

// Router to load controller based on page called
// US1
if ($page === 'home') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->index();
}
// US2
elseif ($page === 'legale') {
    $controller = new \NsAppEcoride\Controller\LegalesController();
    $controller->index();
} elseif ($page === 'philosophie') {
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->philosophie();
} elseif ($page === 'covoiturage') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->all();
} elseif ($page === 'contact') {
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->contact();
}
// US3 US4
elseif ($page === 'trajet') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->show();
}
// US5 - Page complète
elseif ($page === 'trajet-detail') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->detail();
}
// US6
elseif ($page === 'utilisateurs.login') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->login();
} elseif ($page === 'utilisateurs.index') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->index();
} elseif ($page === 'logout') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->logout();
} elseif ($page === 'covoiturages.participer') {
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->participer();
} elseif ($page === 'utilisateurs.recupererPassword') {
    $controller = new \NsAppEcoride\Controller\UtilisateursController();
    $controller->recupererPassword();
}
