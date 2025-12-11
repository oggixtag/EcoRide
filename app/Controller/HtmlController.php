<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class HtmlController extends AppController
{
    public function philosophie()
    {
        echo '<pre>';
        var_dump('HtmlController.philosophie().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('HtmlController.philosophie().calling render()..');
        echo '</pre>';

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.philosophie',
            compact('')
        );
    }

    public function contact()
    {
        echo '<pre>';
        var_dump('HtmlController.contact().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('HtmlController.contact().calling render()..');
        echo '</pre>';

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.contact',
            compact('')
        );
    }
}
