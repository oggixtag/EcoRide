<?php

namespace NsCoreEcoride\Controller;

class Controller
{

    // chemin qui contien les views
    protected $viewPath;
    //template
    protected $template;

    /**pour l'affichage de view
     * @param $view view to charge
     * @param array $variables pour le transfer des variables 
     */
    protected function render($view, $variables = [])
    {
        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).render().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).render().$view:' . $view . '.');
        echo '</pre>';

        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).render().require1:' . $this->viewPath . str_replace('.', '/', $view) . '.php' . '.');
        echo '</pre>';

        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).render().require2:' . $this->viewPath . 'templates/' . $this->template . '.php' . '.');
        echo '</pre>';

        ob_start();
        extract($variables);
        //le require est au meme niveau du coup il a accès aux deux variables: $posts et $categories.
        require($this->viewPath . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();
        require($this->viewPath . 'templates/' . $this->template . '.php');
    }

    protected function forbidden()
    {
        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).forbidden().called.');
        echo '</pre>';

        // Set HTTP status header
        header("HTTP/1.0 403 Forbidden");

        // Set localisation
        //header('Location:index.php?p=403');

        die('Acces interdit');
    }

    protected static function notFound()
    {
        echo '<pre>';
        var_dump('Controller(NsCoreEcoride).notFound().called.');
        echo '</pre>';

        // Set HTTP status header
        header("HTTP/1.0 404 Not Found");

        // Set localisation
        //header('Location:index.php?p=404');

        die('Page not found');
    }

    /*public static function getTitleSite(): string
    {
        return self::$title;
    }

    public static function setTitleSite($title)
    {
        self::$title = $title;
    }*/
}
