<?php

namespace NsAppEcoride\Controller;

class LegalesController extends AppController
{
    public function __construct()
    {
        echo '<pre>';
        var_dump('LegalsController.__construct()');
        echo '</pre>';

        echo '<pre>';
        var_dump('LegalsController.__construct().calling parent::__construct()');
        echo '</pre>';
        parent::__construct();

        /*echo '<pre>';
        var_dump('LegalsController.__construct().calling $this->loadModel for Covoiturage');
        echo '</pre>';
        $this->loadModel('');*/
    }

    // la page d'acceuil qui liste nos differents articles
    public function index()
    {
        echo '<pre>';
        var_dump('LegalsController.index().called.');
        echo '</pre>';

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'legales.index',
            compact('')
        );
    }
}
