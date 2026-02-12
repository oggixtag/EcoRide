<?php

use PHPUnit\Framework\TestCase;
use NsAppEcoride\Model\CovoiturageModel;

require_once __DIR__ . '/../../app/App.php';

class US01_RechercheTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        // On utilise la vraie connexion DB car le modèle l'exige dans son constructeur,
        // même si on ne l'utilise pas pour la méthode filterResults.
        $db = \App::getInstance()->getDb();
        $this->model = new CovoiturageModel($db);
    }

    public function testFiltrerParPrixMin()
    {
        $trajets = [
            (object) ['covoiturage_id' => 1, 'prix_personne' => 10, 'energie' => 'essence', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 2, 'prix_personne' => 20, 'energie' => 'electrique', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 3, 'prix_personne' => 30, 'energie' => 'diesel', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
        ];

        $filters = ['prix_min' => 15];
        $result = $this->model->filterResults($trajets, $filters);

        $this->assertCount(2, $result);
        $this->assertEquals(2, $result[0]->covoiturage_id);
        $this->assertEquals(3, $result[1]->covoiturage_id);
    }

    public function testFiltrerParPrixMax()
    {
        $trajets = [
            (object) ['covoiturage_id' => 1, 'prix_personne' => 10, 'energie' => 'essence', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 2, 'prix_personne' => 20, 'energie' => 'electrique', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 3, 'prix_personne' => 30, 'energie' => 'diesel', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
        ];

        $filters = ['prix_max' => 25];
        $result = $this->model->filterResults($trajets, $filters);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]->covoiturage_id);
        $this->assertEquals(2, $result[1]->covoiturage_id);
    }

    public function testFiltrerParDuree()
    {
        // Trajet 1: 1h
        // Trajet 2: 3h
        $trajets = [
            (object) [
                'covoiturage_id' => 1, 'prix_personne' => 10, 'energie' => 'essence',
                'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00',
                'date_arrivee' => '2023-01-01', 'heure_arrivee' => '11:00:00'
            ],
            (object) [
                'covoiturage_id' => 2, 'prix_personne' => 20, 'energie' => 'essence',
                'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00',
                'date_arrivee' => '2023-01-01', 'heure_arrivee' => '13:00:00'
            ],
        ];

        $filters = ['duree_max' => 2]; // Max 2h
        $result = $this->model->filterResults($trajets, $filters);

        $this->assertCount(1, $result);
        $this->assertEquals(1, $result[0]->covoiturage_id);
    }

    public function testFiltrerParEnergieEco()
    {
        $trajets = [
            (object) ['covoiturage_id' => 1, 'prix_personne' => 10, 'energie' => 'Essence', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 2, 'prix_personne' => 20, 'energie' => 'Electrique', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 3, 'prix_personne' => 30, 'energie' => 'Hybride', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
        ];

        // 'Electrique' normalisé est 'electrique' -> Eco
        // 'Hybride' normalisé est 'hybride' -> Standard (selon la logique actuelle, seul 'electrique' est éco)
        
        $filters = ['energie' => ['ecologique']];
        $result = $this->model->filterResults($trajets, $filters);

        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0]->covoiturage_id);
    }

    public function testFiltrerParEnergieStandard()
    {
        $trajets = [
            (object) ['covoiturage_id' => 1, 'prix_personne' => 10, 'energie' => 'Essence', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
            (object) ['covoiturage_id' => 2, 'prix_personne' => 20, 'energie' => 'Electrique', 'date_depart' => '2023-01-01', 'heure_depart' => '10:00:00'],
        ];

        $filters = ['energie' => ['standard']];
        $result = $this->model->filterResults($trajets, $filters);

        $this->assertCount(1, $result);
        $this->assertEquals(1, $result[0]->covoiturage_id);
    }
}
