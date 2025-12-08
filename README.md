# EcoRide

🌿 EcoRide : La Plateforme de Covoiturage Écologique

💡 Aperçu du Projet
EcoRide est une startup française dont l'objectif est de réduire l'impact environnemental des déplacements en facilitant et en encourageant le covoiturage. Notre plateforme web est conçue pour être la référence principale pour les voyageurs soucieux de l'environnement qui recherchent une solution de transport économique et responsable.Le projet a été initié par José, notre Directeur Technique, et vise à offrir une solution de covoiturage exclusivement pour les déplacements en voiture.

🛠️ Stack Technique
Ce projet est développé en utilisant une architecture classique de type client-serveur avec les technologies suivantes :
Composant
Technologie
Description
Front-end (Client)JavaScript (Vanilla ou librairie/framework à définir)
Gestion de l'interface utilisateur, de l'interactivité et de la consommation des APIs.
Back-end (Serveur)
PHP
Logique métier, gestion des requêtes HTTP et interaction avec la base de données.Base de DonnéesMySQLStockage structuré des données (utilisateurs, trajets, réservations, etc.).

🚀 Fonctionnalités Clés
EcoRide permettra aux utilisateurs de :
1. Publier un Trajet : Les conducteurs peuvent proposer leurs trajets en spécifiant le départ, la destination, la date, l'heure, le nombre de places disponibles et le prix.
2. Rechercher un Trajet : Les passagers peuvent rechercher des trajets disponibles en fonction de leurs critères (départ, destination, date).
3. Réserver une Place : Les passagers peuvent réserver et potentiellement payer leur place sur un trajet.
4. Gestion de Profil : Création et modification de leur profil utilisateur (conducteur/passager).
5. Système d'Évaluation : Permettre aux utilisateurs de s'évaluer mutuellement après un trajet.

⚙️ Installation et Configuration
Pour démarrer avec le projet EcoRide, suivez les étapes ci-dessous.
1. PrérequisAssurez-vous que les éléments suivants sont installés sur votre machine :
Serveur Web : Apache, Nginx (généralement inclus dans XAMPP/WAMP/MAMP).
PHP : Version 8.0 ou supérieure recommandée.
MySQL : Version 5.7 ou supérieure.
Node.js & npm/yarn (pour le front-end si des outils de build/packages sont utilisés).
2. Base de Données, répertoire scripts_DB
a/Créez la base de données MySQL nommée ecoride_db:
CREATE DATABASE ecoride_db;
b/Importez le schéma, pour cela:
mysql -u [votre_utilisateur] -p ecoride_db < DDM.sql
c/Importez les données 
DML.sql
c.1/Script rollback:
DDL_rollback.sql => reprendre du point a.
d/Configurez les informations de connexion à la base de données dans le fichier de configuration du back-end (config/config.php).
3. Installation du ProjetCloner le dépôt :Bashgit clone https://www.linternaute.fr/dictionnaire/fr/definition/depot/ ecoride
cd ecoride
Configuration du Front-end (JavaScript) :
Si des dépendances npm/yarn sont utilisées :
Bash
cd frontend/
npm install
# ou yarn install
Configuration du Back-end (PHP) :Si Composer est utilisé :Bashcd backend/
composer install
4. LancementDéplacez les fichiers du projet dans le répertoire racine de votre serveur web local (ex: htdocs pour XAMPP) et accédez à l'application via votre navigateur.URL typique de développement : http://localhost/ecoride/

🏗️ Structure du Projet.

ecoride/
├── app/                  				# Cœur de l'application
│   ├── Controllers/      				# Gère les requêtes utilisateur et interagit avec les Modèles
│   │   └── CovoiturageController.php   # Gère la page d'accueil (US 1)
│   │   └── LegalegeController.php      # Gère les mentions légales 
│   ├── Models/           				# Gère les données et la logique métier (interaction BDD)
│   │   └── CovoiturageModel.php  		# Classe pour les données et requêtes 'covoiturage'
├── views/                				# Fichiers de présentation (HTML + PHP)
│   ├── templates/         				# Templates principaux (header, footer)
│   │   └── default.php
│   ├── covoiturages/
│   │   └── index.php        			# Contient US 1 : Page d’accueil 
│   │   └── journey.php        			# Contient les résultats de la recherche du US 1
│   ├── legales/
│   │   └── index.php       			# Vue mentions légales
├── core/                 				# Classes fondamentales (génériques et réutilisables)
│   ├── Database.php      				# Connexion à la BDD
│   ├── Router.php        				# Moteur de routage
│   └── View.php          				# Gestion des vues et inclusion des gabarits			
├── public/               				# Point d'entrée public (seul dossier accessible par le web)
│   ├── index.php         				# Le "Front Controller" qui démarre tout
│   ├── css/              				# Styles CSS
│   │   └── app.css   			        # style de l'application
│   │   └── style_covoiturage.css       # style US 1
│   │   └── style_legale.css   			# style pour les mentions légales 
│   ├── js/               				# Scripts JavaScript
│   └── assets/           				# Images, Favicons
├── vendor/               				# Dépendances Composer (si utilisées)
└── .env                  				# Fichier de configuration

🤝 Contribution
Nous accueillons avec plaisir les contributions !
Si vous êtes un développeur souhaitant aider Andrea à faire d'EcoRide un succès :
Faites un "fork" du dépôt.
Créez une nouvelle branche pour votre fonctionnalité (git checkout -b feature/nouvelle-fonctionnalite).
Faites vos changements.Committez vos changements (git commit -m 'feat: Ajout de la nouvelle fonctionnalité X').
Poussez la branche (git push origin feature/nouvelle-fonctionnalite).
Ouvrez une Pull Request détaillée.
Note : Conformément à notre approche de développement web, nous utilisons le Responsive Web Design pour assurer une expérience utilisateur optimale sur  les appareils et n'utilisons pas de framework CSS.

📧 Contact
Pour toute question ou demande, veuillez contacter :Andrea - [andreaoggix@gmail.com][https://www.linkedin.com/in/andrea-moriggi-65b73935/]
