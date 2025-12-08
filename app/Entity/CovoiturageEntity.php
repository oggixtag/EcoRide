<?php

namespace NsAppEcoride\Entity;

use NsCoreEcoride\Entity\Entity;

class CovoiturageEntity extends Entity
{

    public function __construct()
    {
        echo '<pre>';
        var_dump('CovoiturageEntity.__construct().');
        echo '</pre>';
    }

    public function getUrl()
    {
        return 'index.php?p=trajet&id=' . $this->covoiturage_id;
    }

    public function getExtrait()
    {
        $html = '<a href="' . $this->getUrl() . '">Lire les détails </a>';
        return $html;
    }

    public function getTitle()
    {
        echo '<pre>';
        var_dump('CovoiturageEntity.getTitle().');
        echo '</pre>';

        return htmlspecialchars($this->title);
    }

    /**
     * Sinon 
     * Uncaught Error: Object of class NsAppBlog\Entity\CovoiturageEntity could not be converted to string
     */
    public function __toString()
    {

        echo '<pre>';
        var_dump('CovoiturageEntity.__toString() calling getTitle()..');
        echo '</pre>';

        // to show the title of the article in the select on lastByCategory method.
        return $this->getTitle();
    }
}
