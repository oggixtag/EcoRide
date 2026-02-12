# Rapport de Tests Unitaires - EcoRide

**Date :** 12 Février 2026
**Statut Global :** ✅ SUCCÈS
**Environnement :** PHP 8.2.12, PHPUnit 11.5.48, XAMPP (Windows)

## 1. Résumé des Résultats

L'ensemble de la suite de tests unitaires a été exécuté avec succès. Tous les tests, y compris ceux nouvellement créés et traduits, passent sans erreur.

- **Total des tests :** 42
- **Assertions :** 112
- **Échecs (Failures) :** 0
- **Erreurs (Errors) :** 0
- **Avertissements (Warnings) :** 1 (Lié à la sortie de debug)

## 2. Couverture des User Stories

Les tests couvrent les fonctionnalités critiques suivantes :

- **US01 - Recherche de Covoiturage :** Vérifie la recherche de trajets.
- **US07 - Inscription :** Vérifie la création de compte utilisateur.
- **US08 - Gestion Profil & Voiture :**
    - Tests de création, modification et suppression de voitures.
    - Vérification des contraintes d'appartenance (un utilisateur ne peut supprimer que ses voitures).
- **US09 - Publication de Trajet :**
    - Vérifie la déduction de crédits lors de la publication.
    - Vérifie l'échec de la publication si le solde est insuffisant.
- **US10 - Historique :** Vérifie la récupération des trajets passés.
- **US11 - Cycle de Vie Voyage :** Vérifie le démarrage et la terminaison des trajets.
- **US12 - Administration :** Vérifie la gestion des utilisateurs et la suspension.
- **US13 - Espace Employé :** Vérifie la validation des avis, la connexion employée et l'envoi d'emails.

## 3. Détails d'Implémentation & Snippets de Code

### 3.1 Isolation des Tests (Database)

Pour éviter les conflits de données (ex: `Duplicate entry` pour les emails) et garantir que chaque test est indépendant, nous utilisons `uniqid()` pour générer des emails uniques à chaque exécution de test.

**Exemple : `tests/Unit/US08_VoitureTest.php`**

```php
protected function setUp(): void
{
    $this->db = \App::getInstance()->getDb();
    $this->voitureModel = new VoitureModel($this->db);
    $this->utilisateurModel = new UtilisateurModel($this->db);

    // Créer un utilisateur de test avec email unique pour éviter les conflits
    $uniqueId = uniqid();
    $email = "test.voiture.$uniqueId@mail.com";
    
    $this->utilisateurModel->query(
        "INSERT INTO utilisateur (nom, prenom, email, password, pseudo, role_id, credit) VALUES ('TestVoiture', 'User', ?, 'pass', ?, 2, 20)", 
        [$email, "TestVoiture_$uniqueId"]
    );
    
    $user = $this->utilisateurModel->findByEmail($email);
    $this->idUtilisateurTest = $user->utilisateur_id;
}
```

### 3.2 Injection de Données de Test pour l'Authentification

Les tests d'administration et d'espace employé (`US12`, `US13`) nécessitent des utilisateurs spécifiques (Admin, Employé). Pour éviter de dépendre des données de seed (qui peuvent être absentes ou modifiées), nous injectons ces données directement dans le `setUp` du test. Nous gérons également les clés étrangères (`departement`, `poste`).

**Exemple : `tests/Unit/US13_EspaceEmployeTest.php`**

```php
protected function setUp(): void
{
    // ... initialisation DB ...

    // 1. Insérer les Postes nécessaires (Admin=1, Employé=2)
    $this->db->query("INSERT IGNORE INTO poste (id_poste, intitule) VALUES (1, 'Administrateur'), (2, 'Employé')");
    
    // 2. Insérer un Département pour satisfaire la clé étrangère id_dept
    $this->db->query("INSERT IGNORE INTO departement (id_dept, nom_dept) VALUES (1, 'IT')");

    // 3. Insérer un Employé de test lié à ce département
    $this->db->prepare(
        "INSERT INTO employe (nom, prenom, email, password, pseudo, id_poste, id_dept) VALUES ('TestEmploye', 'Sophie', 'test.employe@mail.com', 'pwd_test', 'testemploye', 2, 1)", 
        []
    );
    $this->employeId = $this->db->getLastInsertId();
}
```

### 3.3 Gestion des Rate Limits (Mailtrap)

Lors des tests d'envoi d'emails (`US13`), nous avons rencontré des erreurs de limite de débit API avec Mailtrap ("Too many emails per second"). Une pause explicite a été ajoutée pour stabiliser les tests.

**Exemple : `tests/Unit/US13_EspaceEmployeTest.php`**

```php
public function testRefusAvisEtEmail()
{
    // Pause pour éviter le rate limit de Mailtrap
    sleep(2);

    // Simuler connexion employé
    $this->assertEquals(2, $this->auth->loginEmploye('testemploye', 'pwd_test'));
    
    // ... reste du test ...
}
```

### 3.4 Validation Précise des Mises à Jour

Nous avons amélioré la classe `MysqlDatabase` avec une méthode `executeRowCount` pour vérifier précisément si une requête `UPDATE` (comme la déduction de crédits) a réellement modifié une ligne, ce qui est crucial pour la logique métier (retourner `false` si solde insuffisant).

**Model : `UtilisateurModel::deduireCredit`**

```php
public function deduireCredit($utilisateur_id, $montant_credit)
{
    // ... vérifications ...
    
    // Retourne le nombre de lignes affectées, pas juste le succès de la commande SQL
    $rowCount = $this->db->executeRowCount(
        "UPDATE utilisateur SET credit = credit - ? WHERE utilisateur_id = ? AND credit >= ?",
        [$montant_credit, $utilisateur_id, $montant_credit]
    );

    return $rowCount > 0;
}
```

## 4. Conclusion

La suite de tests est maintenant robuste, isolée et couvre les nouvelles fonctionnalités demandées. Elle peut être exécutée de manière fiable dans l'environnement de développement actuel.
