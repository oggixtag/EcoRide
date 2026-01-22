**Implementation US 6 : Participer à un covoiturage**

****Nouvelle fonctionnalité****

1.  Page login
2.  Page index
3.  Visibilité bouton Connexion (ou Déconnexion)
4.  Participer / Réserver une place
5.  Visibilité bouton Détail
6.  Remplacement du lien

----- 

****Bugs****

1.	page trajet 
2.	page utilisateur.login
3.	**TODO** page utilisateur.index

----- 
      
Ci-dessous les spécificités:

1.  Page login, cette page permet à l’utilisateur de s' authentifier. Pour cela:
    1.  Créer la page.
    2.  style simple.
    3.  Respecter les couleurs du site.
    4.  création d' un box au centre de la page.
    5.  fichier `css` associé `style_utilisateur.css`
    6.  renseigner les champs pseudo et password:
        1.  les deux sont obligatoires.
        2.  La vérification si les champs ont été renseignés se fait avec code js dans la page en elle-même.
    7.  Le bouton submit doit renvoyer vers la page index.php, confer point majeur, numéro 2, dans cette liste.
2.  Page index, cette page permet d’afficher les informations liées à l'utilisateur.
    1.  Pour l’affichage utiliser le fichier `css` créé au point précédent
    2.  Schéma relationnel du database, créer des sections distinctes pour:
        1.  Informations personnelles: nom, prenom, email, password, telephone, adresse, date\_naissance, photo, pseudo
        2.  Role
            1.  Conducteur, il peut:
                  * ajouter, modifier et supprimer un trajet
            2.  Passager, il peut:
                  * réserver une place (ou libérer une place)
        3.  Avis
        4.  Voiture
        5.  Covoiturage
3.  Visibilité bouton Connexion (ou Déconnexion), il doit être dynamisée en fonction de la session de l'utilisateur, confer `EcoRide\app\Views\templates\default.php`
4.  **`Participer / Réserver une place`** -\> `EcoRide\app\Views\covoiturages\trajet_detail.php` -\> ligne 168.
    Au moment que l'utilisateur appuie ce bouton, le système doit vérifier si l’utilisateur est connecté, pour cela il y 2 cas:
    1.  Connecté: la session est active
        1.  Mise à jour du crédit de l’utilisateur : ```credit_restant = credit - 2```.
        2.  Enregistrement du trajet dans les tables
    2.  Non connecté: l’utilisateur sera redirigé vers la page `EcoRide\app\Views\utilisateurs\login.php` pour qu’il puisse se connecter.
        1.  Récupérer l’identifiant du trajet.
        2.  Remplacer l’indication ‘Connexion’ avec la mention ‘Effectuer le login avant de participer au covoiturage’, page `login.php` ligne 3.
        3.  Rediriger l’utilisateur vers la page `EcoRide/public/index.php?p=trajet_detail.php` avec le bon identifiant du trajet.
5.  **Visibilité bouton Détail** -\> `EcoRide\app\Views\covoiturages\trajet.php` -\>. La visibilité du bouton est en fonction de la session de l’utilisateur:
    1.  Connecté: ajouter la condition ```crédit > 0```
    2.  Non connecté: la seule condition est les places disponibles.
6.  **Remplacement du lien** 
> Pas encore de compte ? \<a href="index.php"\>Retour à l'accueil

Dans `EcoRide\app\Views\utilisateurs\login.php`, le remplacer avec un autre qui permet à l’utilisateur de récupérer sa password en saisissant mail ou pseudo.


-----

_Bugs_

1.  Dans `EcoRide/public/index.php?p=trajet`, j’ai constaté les bugs suivants lorsque l’utilisateur a réservé un trajet:
    1.  le bouton `Détails` disparaît
    2.	le nombre des places disponibles ne change pas
2.	Dans `EcoRide/public/index.php?p=utilisateurs.login`, problème d'affichage
	1.	suppression ‘Retour à l'accueil’
	2.	‘Mot de passe oublié’ doit être un bouton, place-le à droite de ‘Se connecter’
3.	Dans `EcoRide/public/index.php?p=utilisateurs.index`
	1.	les avis ne s'affiche pas, vérification avec la requête ‘‘‘SELECT * FROM avis WHERE utilisateur_id = 1’’’
	2.	‘Mes Voitures’
		1.	les champs suivants ne sont pas valorisés: Année,Places
		2.	ajouter les champs: energie et couleur
	3.	‘Mes Trajets en Covoiturage’
		1.	les champs suivants ne sont pas valorisés: Date, Places disponibles, Prix
		2.	le champ Date indique les secondes, il faut le supprimer


