<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class HtmlController extends AppController
{
    public function philosophie()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.philosophie',
            []
        );
    }

    public function contact()
    {
        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.contact',
            []
        );
    }
}
