<?php

namespace NsCoreEcoride\Model;

use NsCoreEcoride\Database\Database;

class Model
{
    protected $table;
    protected $column;
    protected $db;

    /**
     * On devinet le nom de la table à partir du nom de la classe.
     * je lui passe la classe Database et non MysqlDatabase, car il y a l'héritage.
     */
    // 
    public function __construct(Database $db)
    {
        $this->db = $db;
        // db permet d'arriver à la classe MysqlDatabase

        // si la proprieté a été definée dans le sous-classes
        // ça sera cella-là qu'il sera prise en compte, sinon
        // la table sera automatiment prise du nom de la classe.
        if (is_null($this->table)) {
            // on explode le nom de la class
            $part = explode('\\', get_class($this));
            // on prend la denière partie
            $class_name = end($part);
            // on retire Table du nom de la classe
            $class_name = strtolower(str_replace('Model', '', $class_name));
            // on valorise la proprieté $table avec $class_name
            $this->table = $class_name;
            // on valorise la proprieté $column avec $table.'_id'
            $this->column = $this->table . '_id';
        }
    }

    public function all()
    {
        return $this->query("SELECT * FROM " . $this->table);
    }

    public function find($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE {$this->column} = ?", [$id], true);
    }

    public function update($id, $fields)
    {
        // composition clé valeur pour update
        $sql_part = [];
        $attributes = [];
        foreach ($fields as $key => $value) {

            /*echo '<pre>';
            var_dump('key:' . $key);
            echo '</pre>';*/
            /**
             * string(9) "key:title"
             * string(11) "key:content"
             * string(15) "key:category_id"
             * */

            // on ajout '=?'
            $sql_part[] = $key . '=?';
            $attributes[] = $value;
            /*echo '<pre>';
            var_dump('value:' . $value);
            echo '</pre>';*/
            /**
             * string(29) "value:Mon premier post UPDATE"
             * string(405) "value:Ever feel like your to-do list is plotting against you? With Any.DO, you take back control! This app is like a personal assistant who never takes a coffee break. Keep your plans in check, move tasks around with a simple drag and drop, and strike through completed ones like a productivity ninja. Got too many finished tasks cluttering your list? Just give your device a shake—boom, they’re gone!"
             * string(7) "value:1" */
        }

        // on ajout l'id de l'article
        $attributes[] = $id;

        // chaine de carachetteres 
        $sqlSet = implode(',', $sql_part);

        /**Printing fields following by ?:string(31) "title=?,content=?,category_id=?"
        Printing values that replace ?:array(4) {
        [0]=>
        string(23) "Mon premier post UPDATE"
        [1]=>
        string(399) "Ever feel like your to-do list is plotting against you? With Any.DO, you take back control! This app is like a personal assistant who never takes a coffee break. Keep your plans in check, move tasks around with a simple drag and drop, and strike through completed ones like a productivity ninja. Got too many finished tasks cluttering your list? Just give your device a shake—boom, they’re gone!"
        [2]=>
        string(1) "1"
        [3]=>
        string(1) "1" => cela corresponde à l'id , il a été ajouté , car dans la requête d'update l'id est en dernière positionne .
        } */

        //die();

        return $this->query("UPDATE {$this->table} SET $sqlSet WHERE {$this->column}=?", $attributes, true);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM {$this->table} WHERE {$this->column}=?", [$id], true);
    }

    public function insert($fields)
    {
        /*echo '<pre>';
        echo 'Printing $fields:';
        var_dump($fields);
        echo '</pre>';*/
        /**Printing $fields:array(3) {
  ["title"]=>
  string(6) "sempre"
  ["content"]=>
  string(5) "paolo"
  ["category_id"]=>
  string(1) "1"
} */

        // composition clé valeur pour update
        $sql_part = [];
        $attributes = [];
        foreach ($fields as $key => $value) {

            /*echo '<pre>';
            echo 'Printing $key => $value:';
            var_dump($key);
            var_dump($value);
            echo '</pre>';*/
            /**Printing $key => $value:string(5) "title"
string(6) "sempre"
Printing $key => $value:string(7) "content"
string(5) "paolo"
Printing $key => $value:string(11) "category_id"
string(1) "1" */

            // on ajout '=?'
            $sql_part[] = $key . '=?';
            $attributes[] = $value;
        }

        // chaine de carachetteres 
        $sqlSet = implode(',', $sql_part);
        /*echo '<pre>';
        echo 'Printing fields following by ?:';
        var_dump($sqlSet);
        echo '</pre>';*/
        //Printing fields following by ?:string(31) "title=?,content=?,category_id=?"

        /*echo '<pre>';
        echo 'Printing values that replace ?:';
        var_dump($attributes);
        echo '</pre>';*/
        /**Printing values that replace ?:array(3) {
  [0]=>
  string(6) "sempre"
  [1]=>
  string(5) "paolo"
  [2]=>
  string(1) "1"
} */
        //die();

        return $this->query("INSERT INTO {$this->table} SET $sqlSet ", $attributes, true);
    }

    public function extratList($key, $value)
    {
        // on recupere tous les enregistrements 
        $records = $this->all();
        $return = [];
        foreach ($records as $k => $v) {
            // on renpli le tableas $return avec la clé à estraire et la valeur avec la valeur à extraire.
            $return[$v->$key] = $v->$value;
        }
        return $return;
    }


    /*  Méthode générique pour exécuter une requête SQL
     * 
     * @param string $statement : requête SQL à exécuter
     * @param array|null $attributes : attributs pour la requête préparée
     * @param bool $one : true si on veut un seul résultat
     * @return mixed
     */
    public function query($statement, $attributes = null, $one = false)
    {
        $classe_name = str_replace('Model', 'Entity', get_class($this));

        //die();

        // on a déjà la base des données dans l'objet.
        // s'il y a les attributes
        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                $classe_name,
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                $classe_name,
                $one
            );
        }
    }
}
