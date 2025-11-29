# EcoRide

🌿 EcoRide : La Plateforme de Covoiturage Écologique

💡 Aperçu du ProjetEcoRide est une startup française dont l'objectif est de réduire l'impact environnemental des déplacements en facilitant et en encourageant le covoiturage. Notre plateforme web est conçue pour être la référence principale pour les voyageurs soucieux de l'environnement qui recherchent une solution de transport économique et responsable.Le projet a été initié par José, notre Directeur Technique, et vise à offrir une solution de covoiturage exclusivement pour les déplacements en voiture.

🛠️ Stack TechniqueCe projet est développé en utilisant une architecture classique de type client-serveur avec les technologies suivantes :ComposantTechnologieDescriptionFront-end (Client)JavaScript (Vanilla ou librairie/framework à définir)Gestion de l'interface utilisateur, de l'interactivité et de la consommation des APIs.Back-end (Serveur)PHPLogique métier, gestion des requêtes HTTP et interaction avec la base de données.Base de DonnéesMySQLStockage structuré des données (utilisateurs, trajets, réservations, etc.).

🚀 Fonctionnalités ClésEcoRide permettra aux utilisateurs de :Publier un Trajet : Les conducteurs peuvent proposer leurs trajets en spécifiant le départ, la destination, la date, l'heure, le nombre de places disponibles et le prix.Rechercher un Trajet : Les passagers peuvent rechercher des trajets disponibles en fonction de leurs critères (départ, destination, date).Réserver une Place : Les passagers peuvent réserver et potentiellement payer leur place sur un trajet.Gestion de Profil : Création et modification de leur profil utilisateur (conducteur/passager).Système d'Évaluation : Permettre aux utilisateurs de s'évaluer mutuellement après un trajet.

⚙️ Installation et ConfigurationPour démarrer avec le projet EcoRide, suivez les étapes ci-dessous.
1. PrérequisAssurez-vous que les éléments suivants sont installés sur votre machine :Serveur Web : Apache, Nginx (généralement inclus dans XAMPP/WAMP/MAMP).PHP : Version 8.0 ou supérieure recommandée.MySQL : Version 5.7 ou supérieure.Node.js & npm/yarn (pour le front-end si des outils de build/packages sont utilisés).
2. Base de DonnéesCréez une base de données MySQL nommée ecoride_db.SQLCREATE DATABASE ecoride_db;
Importez le schéma de la base de données (le fichier ecoride_db.sql sera créé et mis à jour dans le dossier database/).Bashmysql -u [votre_utilisateur] -p ecoride_db < database/ecoride_db.sql
Configurez les informations de connexion à la base de données dans le fichier de configuration du back-end (le chemin exact dépendra de l'organisation PHP, typiquement dans un fichier .env ou config.php).
3. Installation du ProjetCloner le dépôt :Bashgit clone https://www.linternaute.fr/dictionnaire/fr/definition/depot/ ecoride
cd ecoride
Configuration du Front-end (JavaScript) :Si des dépendances npm/yarn sont utilisées :Bashcd frontend/
npm install
# ou yarn install
Configuration du Back-end (PHP) :Si Composer est utilisé :Bashcd backend/
composer install
4. LancementDéplacez les fichiers du projet dans le répertoire racine de votre serveur web local (ex: htdocs pour XAMPP) et accédez à l'application via votre navigateur.URL typique de développement : http://localhost/ecoride/
🏗️ Structure du Projet.
├── backend/                  # Code source PHP (API, Logique métier, BDD)
│   ├── api/                  # Fichiers gérant les points d'API (trajets, utilisateurs, etc.)
│   ├── config/               # Fichiers de configuration (BDD, clés API)
│   ├── src/                  # Classes PHP (modèles, services)
│   └── vendor/               # Dépendances Composer (si utilisées)
├── frontend/                 # Code source JavaScript, HTML, CSS
│   ├── assets/               # Images, polices, fichiers statiques
│   ├── css/                  # Feuilles de style (Sass/CSS purs)
│   ├── js/                   # Fichiers JavaScript (logique du client)
│   └── index.html            # Point d'entrée de l'application
├── database/                 # Fichiers SQL (schéma, migrations)
│   └── ecoride_db.sql        # Schéma de la base de données
└── README.md                 # Ce fichier

🤝 ContributionNous accueillons avec plaisir les contributions ! Si vous êtes un développeur souhaitant aider José à faire d'EcoRide un succès :Faites un "fork" du dépôt.Créez une nouvelle branche pour votre fonctionnalité (git checkout -b feature/nouvelle-fonctionnalite).Faites vos changements.Committez vos changements (git commit -m 'feat: Ajout de la nouvelle fonctionnalité X').Poussez la branche (git push origin feature/nouvelle-fonctionnalite).Ouvrez une Pull Request détaillée.Note : Conformément à notre approche de développement web, nous utilisons le Responsive Web Design pour assurer une expérience utilisateur optimale sur tous les appareils et n'utilisons pas de framework CSS (le CSS est géré manuellement ou via un préprocesseur).

📧 ContactPour toute question ou demande, veuillez contacter :Andrea - [andreaoggix@gmail.com][https://www.linkedin.com/in/andrea-moriggi-65b73935/]
