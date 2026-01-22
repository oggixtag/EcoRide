# Notes US 8 : Espace Utilisateur

## Résumé de l'implémentation
L'implémentation de l'Espace Utilisateur a nécessité plusieurs modifications structurelles et fonctionnelles.

### 1. Base de Données
- Création de la table `preference` pour stocker les préférences utilisateurs (Fumeur, Animaux, etc.).
- Chaque préférence est liée à un `utilisateur_id`.

### 2. Backend
- **UtilisateursController** :
    - Changement du chemin des vues vers `utilisateurs/profile/`.
    - Ajout de la méthode `edit()` pour gérer la mise à jour du profil, le changement de rôle (Passager/Chauffeur) et les préférences.
- **VoituresController** :
    - Création du contrôleur complet pour le CRUD (Index, Add, Edit, Delete).
- **Modèles** :
    - Mise à jour de `UtilisateurModel` avec les méthodes de gestion des préférences (`getPreferences`, `addPreference`, `clearPreferences`, `update`).
    - Création de `VoitureModel` pour la gestion des véhicules.

### 3. Frontend (Vues)
- **Refonte de l'arborescence** :
    - Déplacement de `utilisateurs/index.php` vers `utilisateurs/profile/index.php`.
- **Nouvelles Pages** :
    - `utilisateurs/profile/edit.php` : Formulaire d'édition de profil et préférences.
    - `utilisateurs/voitures/index.php` : Liste des voitures.
    - `utilisateurs/voitures/add.php` & `edit.php` : Formulaires de gestion des voitures.
- **Modifications UI** :
    - Ajout des boutons "Modifier" pour le profil et "Gérer" pour les voitures sur le tableau de bord.
    - Suppression de l'affichage du nombre de places dans la liste des voitures du tableau de bord.
    - Extraction des styles CSS spécifiques vers `public/css/style_utilisateur.css` pour respecter les standards de développement.

### 4. Tests
- Mise en place de **PHPUnit**.
- Création de tests d'intégration dans `tests/Unit/UtilisateurTest.php` pour vérifier la logique des préférences et de la mise à jour utilisateur.

## Points d'attention
- Le rôle "Passager-Chauffeur" est traité techniquement comme un rôle "Chauffeur" (ID 1) mais l'interface permet la distinction visuelle si besoin.
- La suppression des voitures demande une confirmation JavaScript.
