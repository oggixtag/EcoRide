<?php

namespace NsAppEcoride\Controller;

use \NsCoreEcoride\HTML\MyForm;

class CovoituragesController extends AppController
{

    public function __construct()
    {
        echo '<pre>';
        var_dump('CovoituragesController.__construct()');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoituragesController.__construct().calling parent::__construct()');
        echo '</pre>';
        parent::__construct();

        echo '<pre>';
        var_dump('CovoituragesController.__construct().calling $this->loadModel for Covoiturage');
        echo '</pre>';
        $this->loadModel('Covoiturage');
    }

    // la page d'acceuil qui liste nos differents articles
    public function index()
    {
        echo '<pre>';
        var_dump('CovoituragesController.index().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('CovoituragesController.calling all()..');
        echo '</pre>';

        //['posts'=>$post,'categories'=>$categories]
        //compact('posts','categories')

        $covoiturages = $this->Covoiturage->all();

        // ça appelle la page index (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render(
            'covoiturages.index',
            compact('covoiturages')
        );
    }

    public function show()
    {
        echo '<pre>';
        var_dump('CovoituragesController.find().called on methed ' . $_SERVER['REQUEST_METHOD'] . '.');
        echo '</pre>';

        $error_form_empty = false;

        if (empty($_POST['lieu_depart']) && empty($_POST['lieu_arrivee']) && empty($_POST['date'])) {
            $error_form_empty = true;
            echo '<pre>';
            var_dump($_POST);
            echo '</pre>';
        }

        $covoiturage = $this->Covoiturage->find($_POST['lieu_depart']);

        $form = new MyForm($_POST);

        // ça appelle la page journey (C:\xampp\htdocs\EcoRide\app\Views\covoiturages)
        $this->render('covoiturages.journey', compact('covoiturage', 'form', 'error_form_empty'));
    }

    // la page category qui permettra d'afficher la category qu'on suite consulté
    // la partie qui gère le contenu
    public function category()
    {
        echo '<pre>';
        var_dump('CovoituragesController.category().called.');
        echo '</pre>';

        // On récupère les informations de la categorie selectionnée.
        echo '<pre>';
        var_dump('CovoituragesController.category().calling find()..');
        echo '</pre>';
        $categorie = $this->Category->find($_GET['id']);

        // si l'id n'existe pas.
        if ($categorie === false) {
            // methode dans Controller
            $this->notFound();
        }

        /*echo '<pre>';
        var_dump('CovoituragesController.category().calling setTitleSite()..');
        echo '</pre>';
        $app->setTitleSite($categorie->title);*/

        echo '<pre>';
        var_dump('category.calling lastByCategory()..');
        echo '</pre>';
        $articles = $this->Post->lastByCategory($_GET['id']);

        echo '<pre>';
        var_dump('category.calling all()..');
        echo '</pre>';
        $categories = $this->Category->all();

        $this->render(
            'posts.category',
            compact('categorie', 'articles', 'categories')
        );
    }

    // pour présenter à l'utilisateur un article spécifique 
    /*public function show()
    {
        echo '<pre>';
        var_dump('CovoituragesController.show().called.');
        echo '</pre>';

        $article = $this->Post->find($_GET['id']);

        if ($article === false) {
            $this->notFound();
        }

        $this->render('posts.article', compact('article',));
    }*/
}
