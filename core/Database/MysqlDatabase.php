<?php

/* A partir de maintenant, je travail dans le namespace NsBlog */

namespace NsCoreEcoride\Database;

use \PDO;

/**
 * Class Database
 * Permet de centraliser la connection à la base de données
 */
class MysqlDatabase extends Database
{
    private $dbname;
    private $dbusername;
    private $dbpassword;
    private $dbhost;
    private $pdo;


    /*  Constructeur de la classe Database
     * 
     * @param string $dbname : nom de la base de données
     * @param string $dbusername : nom d'utilisateur de la base de données
     * @param string $dbpassword : mot de passe de la base de données
     * @param string $dbhost : hôte de la base de données
     */
    public function __construct($dbname = '', $dbusername = 'root', $dbpassword = '', $dbhost = 'localhost')
    {
        echo '<pre>';
        var_dump('MysqlDatabase.__construct().');
        echo '</pre>';

        $this->dbname = $dbname;
        $this->dbusername = $dbusername;
        $this->dbpassword = $dbpassword;
        $this->dbhost = $dbhost;
    }


    /*  Méthode pour obtenir une instance de PDO
     * Utiliser '\' pour accéder à la classe PDO globale <=> use \PDO;
     * 
     * @return \PDO : instance de PDO
     */
    private function getPdo()
    {
        echo '<pre>';
        var_dump('MysqlDatabase.getPdo called.');
        echo '</pre>';

        /* On vérifie si l'instance de PDO n'existe pas déjà
         * Si elle n'existe pas, on la crée
         */
        if ($this->pdo === null) {

            echo '<pre>';
            var_dump('MysqlDatabase.getPdo.$this->pdo === null.');
            echo '</pre>';

            $dns = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname;
            $pdo = new PDO($dns, $this->dbusername, $this->dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    /*  Méthode pour exécuter une requête SQL ; fetch et fetchAll
     * 
     * @param string $statement : requête SQL à exécuter
     * @param string $class_name : nom de la classe pour le fetch
     * @param string $one : pour traiter un au plusier enregistrements 
     */
    public function query($statement, $class_name = null, $one = false)
    {
        echo '<pre>';
        var_dump('MysqlDatabase.query().');
        echo '</pre>';

        $req = $this->getPdo()->query($statement);

        if (
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            echo '<pre>';
            var_dump('MysqlDatabase.query().UPDATEorINSERTorDELETE.');
            echo '</pre>';

            return $req;
        }

        if ($class_name === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }

        echo '<pre>';
        var_dump('MysqlDatabase.query().return');
        echo '</pre>';

        return $datas;
    }

    /*  Méthode pour exécuter une requête SQL préparée ; fetch ou fetchAll
     *  
     * @param string $statement : requête SQL à exécuter
     * @param array $attributes : tableau des paramètres
     * @param string $class_name : nom de la classe pour le fetch
     * @param string $one : pour traiter un au plusier enregistrements
     */
    public function prepare($statement, $attributes, $class_name = null, $one = false)
    {
        echo '<pre>';
        var_dump('MysqlDatabase.prepare().called.');
        echo '</pre>';

        echo '<pre>';
        var_dump('MysqlDatabase.prepare().$statement:' . $statement . '.');
        echo '</pre>';

        echo '<pre>';
        var_dump('MysqlDatabase.prepare().calling $this->getPdo()->prepare($statement).');
        echo '</pre>';

        $req = $this->getPdo()->prepare($statement);

        $res = $req->execute($attributes); //<=> tableau des paramètres

        if (
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            echo '<pre>';
            var_dump('MysqlDatabase.prepare().UPDATEorINSERTorDELETE.');
            echo '</pre>';

            return $res;
        }

        echo '<pre>';
        var_dump('MysqlDatabase.prepare().$class_name:' . $class_name . '.');
        echo '</pre>';

        if ($class_name === null) {
            echo '<pre>';
            var_dump('MysqlDatabase.prepare().setFetchMode.FETCH_OBJ.');
            echo '</pre>';
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            echo '<pre>';
            var_dump('MysqlDatabase.prepare().setFetchMode.FETCH_CLASS.');
            echo '</pre>';
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            echo '<pre>';
            var_dump('MysqlDatabase.prepare().un seul enregistrement.');
            echo '</pre>';
            $datas = $req->fetch();
        } else {
            echo '<pre>';
            var_dump('MysqlDatabase.prepare().plusieur enregistrements.');
            echo '</pre>';
            $datas = $req->fetchAll();
        }

        echo '<pre>';
        var_dump('MysqlDatabase.prepare().return.');
        echo '</pre>';

        return $datas;
    }

    /**
     * Recupère le dernier enregistrement 
     * @return int
     */
    public function getLastInsertId()
    {
        echo '<pre>';
        var_dump('MysqlDatabase.getLastInsertId().called.');
        echo '</pre>';

        return $this->getPdo()->lastInsertId();
    }
}
