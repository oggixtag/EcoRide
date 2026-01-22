****préambule****

Un  Visiteur  peut  devenir  Utilisateur  en  se  créant  un  compte,  pour  cela,  il  doit  fournir  un pseudo ainsi qu’un mail suivi d’un mot de passe.
Il est important de demander un mot de passe sécurisé.
A la création du compte, l’utilisateur bénéficie de 20 crédits.

****Déscription nouvelle fonctionnalité****

1.  modification bouton "connexion" 'EcoRide\app\Views\templates\default.php' en "S'inscrir|Se connecter"

    1. si on appuye "S'inscrir" -> page utilisateurs.inscrir -> affichage:
		1. pseudo
		2. Mot de passe sécurisé
		3. Adresse mail
		3. bouton "S'inscrir"
		4. bouton "Effacé"
		5. insérez le lien "Tu as déjà un compte ? Se connecter"
		6. concernant l'architecture MVC
			1. créer la méthode 'inscrir' dans le controlleur 'EcoRide\app\Controller\UtilisateursController.php'				1. la page a été déjà créée 'EcoRide\app\Views\utilisateurs\inscrir.php'
			2. si besoin utiliser le modelle assosié 'EcoRide\app\Model\UtilisateurModel.php' -> table 'visiteur_utilisateur'
				1. vérifier l'unicité des champs mail et pseudo dans les tables visiteur_utilisateur et utilisateur. 
					1. si le pseudo existe déjà dans la base, afficher un message à l'écran. Pour cela, tu utilises JavaScript.
					2. si le mail existe déjà dans la base, afficher un message à l'écran. Pour cela, tu utilises JavaScript.
			3. si besoin utiliser l'entité assosié 'EcoRide\app\Entity\UtilisateurEntity.php'
	2. si on appuye "Se connecter" -> page utilisateurs.login -> affichage: 
		1. Pseudo
		2. Mot de passe
		3. bouton "Se connecter"
		4. bouton "Mot de passe oublié"
		5. inserere le lien "inscris-toi avecton adresse e-mail"
2.	CSS associé : style_utilisateur.css 'EcoRide\public\css'
3.	concernant la page 'EcoRide\app\Views\utilisateurs\index.php'
	1. l'utilisateur se trouve dans la table 'visiteur_utilisateur' avec 'statut_mail_id = 1' -> la section 'Mes Informations Personnelles' doit indiquer "mail à confirmer"
	2. l'utilisateur se trouve dans la table 'visiteur_utilisateur' avec 'statut_mail_id = 2' -> la section 'Mes Informations Personnelles' doit afficher les champs Nom,Prénom,Email,Téléphone,Date de Naissance comme obligatoires 
		1. vérification champs côté front-end 
		2. le bouton 'enregistrement' permet les actions suivants:
			1. enregistrement dans la table 'utilisateur'
			2. suppression dans la table 'visiteur_utilisateur'
4.	implémenter l'envoie d'un mail
