<?php

namespace NsAppEcoride\Controller;

use NsCoreEcoride\Controller\Controller;
use App;

class AppController extends Controller
{
    protected $template = 'default';

    public function __construct()
    {
        $this->viewPath = ROOT . '/app/Views/';
    }

    /**Prende en parametre le modelle de table à charger */
    protected function loadModel($modelName)
    {
        return $this->$modelName = App::getInstance()->getTable($modelName);
    }

    protected function getLastInsertId()
    {
        return App::getInstance()->getDb()->getlastInsertId();
    }
}
