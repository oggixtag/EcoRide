
## US 8 : Espace Utilisateur

### Préambule

Un Utilisateur, peut depuis son espace, sélectionner s’il est « chauffeur », « passager » ou les deux. 

Quand il devient « chauffeur » ou « passager-chauffeur », il doit obligatoirement fournir les informations :

1. Plaque d’immatriculation
2. Date de première immatriculation
3. Modèle, couleur ainsi que la marque du ou des véhicules (un « chauffeur » peut avoir plusieurs véhicules)
4. Nombre de place disponible
5. Préférences, où il doit notamment fournir comme information, s’il accepte : 
    * Fumeur / non-fumeur
    * Animal / pas d’animal

Cependant, le conducteur doit pouvoir ajouter ses propres préférences en plus de celle-ci. 

Quand un Utilisateur, devient « passager », il ne doit pas saisir d’information spécifique. 

### Implémentation

1. // 'photo' => ... gestion upload 
2. EcoRide\app\Views\utilisateurs\index.php -> section "Mes Informations Personnelles" -> ajouter le bouton "Modifier" à le même niveau de "Mes Informations Personnelles"
    * EcoRide\app\Controller\UtilisateursController.php -> 'role_id' => 2 // Par défaut Passager
        * EcoRide\app\Views\utilisateurs\index.php -> prévoir le changement de rôle « passager » , « chauffeur » ou « passager-chauffeur »
        * Quand il devient « chauffeur » ou « passager-chauffeur », pour fournir les informations
3. EcoRide\app\Views\utilisateurs\index.php -> section "Mes Voitures" -> suppression l'indication "place"
4. les informations :
	* Plaque d’immatriculation
	* Date de première immatriculation
	* Modèle, couleur ainsi que la marque du ou des véhicules (un « chauffeur » peut avoir plusieurs véhicules)
	* Nombre de place disponible
	* Préférences
5. EcoRide\app\Views\utilisateurs\index.php -> section "Mes Voitures" -> ajouter le bouton "Gérer" à le même niveau de "Mes Voitures"
	* si l'utilisateur appuie ce bouton il doit être rédirigé vers EcoRide\app\Views\utilisateurs\voitures\index.php
	* dépuis ce répertoire 'EcoRide\app\Views\utilisateurs\voitures\' il doit pouvoir modifier les informations de la voiture qui a été selectinnée. Le fonctionnalité CRUD sont fornit dans les page EcoRide\app\Views\utilisateurs\voitures\add.php, EcoRide\app\Views\utilisateurs\voitures\edit.php et EcoRide\app\Views\utilisateurs\voitures\delete.php