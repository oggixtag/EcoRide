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

// chargement controller
if ($page === 'home') {
    echo '<pre>';
    var_dump('page index; $page===home; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
    echo '</pre>';
    $controller = new \NsAppEcoride\Controller\CovoituragesController();
    $controller->index();
} elseif ($page === 'journey') {
    echo '<pre>';
    var_dump('page index; $page===journey; nouvelle instantiation new \NsAppEcoride\Controller\CovoiturageController().');
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
