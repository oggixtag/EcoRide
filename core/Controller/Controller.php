<?php

namespace NsCoreEcoride\Controller;

class Controller
{

    // chemin qui contien les views
    protected $viewPath;
    //template
    protected $template;

    /**pour l'affichage de view
     * @param $view view à charger
     * @param array $variables pour le transfer des variables 
     */
    protected function render($view, $variables = [])
    {
        ob_start();
        extract($variables);

        require($this->viewPath . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();
        require($this->viewPath . 'templates/' . $this->template . '.php');
    }

    protected function forbidden()
    {
        // Set HTTP status header
        header("HTTP/1.0 403 Forbidden");

        // Set localisation
        //header('Location:index.php?p=403');

        die('Acces interdit');
    }

    protected static function notFound()
    {
        // Set HTTP status header
        header("HTTP/1.0 404 Not Found");

        // Set localisation
        //header('Location:index.php?p=404');

        die('Page not found');
    }
}
