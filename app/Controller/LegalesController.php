<?php

namespace NsAppEcoride\Controller;

class LegalesController extends AppController
{
    public function __construct()
    {
        parent::__construct();
    }

    // la page d'acceuil qui liste nos differents articles
    public function index()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'legales.index',
            []
        );
    }
}
