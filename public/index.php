<?php
echo '<pre>';
var_dump('page index: ecoride/public/index.php');
echo '</pre>';

define('ROOT', dirname(__DIR__));
echo '<pre>';
var_dump('page index.define ROOT:' . ROOT);
echo '</pre>';

echo '<pre>';
var_dump('page index.calling:' . ROOT . '/app/App.php');
echo '</pre>';
require(ROOT . '/app/App.php');
App::load();

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'home';
}
echo '<pre>';
var_dump('page index.printing $page:' . $page);
echo '</pre>';

// naming : controller + route

// Router to load controller based on page called
// US1
if ($page === 'home') {
    echo '<pre>';
    var_dump('page index; $page===home; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->index();
} elseif ($page === 'trajet') {
    echo '<pre>';
    var_dump('page index; $page===trajet; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->show();
} elseif ($page === 'legale') {
    echo '<pre>';
    var_dump('page index; $page===legale; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\LegalesController();
    $controller->index();
}
// US2
elseif ($page === 'philosophie') {
    echo '<pre>';
    var_dump('page index; $page===philosophie; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->philosophie();
} elseif ($page === 'covoiturages') {
    echo '<pre>';
    var_dump('page index; $page===covoiturages; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->all();
} elseif ($page === 'contact') {
    echo '<pre>';
    var_dump('page index; $page===contact; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\HtmlController();
    $controller->contact();
}
