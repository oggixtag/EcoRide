<?php
define('ROOT', __DIR__);
require 'app/App.php';
App::load();
$db = App::getInstance()->getDb();
$model = new \NsAppEcoride\Model\EmployeModel($db);

$covoiturages = $model->recupererCovoituragesParJour();
echo "Covoiturages JSON:\n";
echo json_encode($covoiturages) . "\n";

$credits = $model->recupererCreditsParJour();
echo "\nCredits JSON:\n";
echo json_encode($credits) . "\n";
