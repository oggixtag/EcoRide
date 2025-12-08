<?php

namespace NsAppEcoride\Controller;

use NsCoreEcoride\Controller\Controller;
use App;

class AppController extends Controller
{
    protected $template = 'default';

    public function __construct()
    {
        echo '<pre>';
        var_dump('AppController(NsAppEcoride).__construct().called.');
        echo '</pre>';

        $this->viewPath = ROOT . '/app/Views/';

        echo '<pre>';
        var_dump('AppController(NsAppEcoride).__construct().printing $this->viewPath:' . $this->viewPath);
        echo '</pre>';
    }

    /**Prende en parametre le modelle de table à charger */
    protected function loadModel($modelName)
    {
        echo '<pre>';
        var_dump('AppController(NsAppEcoride).loadModel().called for: ' . $modelName . '.');
        echo '</pre>';
        return $this->$modelName = App::getInstance()->getTable($modelName);
    }

    protected function getLastInsertId()
    {
        echo '<pre>';
        var_dump('AppController(NsAppEcoride).getLastInsertId().called to call App::getInstance()->getDb()->getlastInsertId().');
        echo '</pre>';
        return App::getInstance()->getDb()->getlastInsertId();
    }
}
