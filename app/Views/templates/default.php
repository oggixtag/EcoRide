<?php
echo '<pre>';
var_dump('page default: ecoride\app\Views\templates\default.php');
echo '</pre>';
?>
<!DOCTYPE html>
<html lang="fr">

<body>

    <header class="main-header">
        <div class="container nav-content">
            <div class="logo">
                <a href="index.php">EcoRide 🌿</a>
            </div>

            <nav class="main-nav">
                <ul>
                    <!-- Retour vers la page d’accueil (toujours présent) -->
                    <li><a href="index.php?p=philosophie" class="active">Philosophie</a></li>

                    <!-- Accès aux covoiturages (affichage des voyage dans la base de donné) -->
                    <li><a href="index.php?p=covoiturage">Covoiturages</a></li>

                    <!-- Contact -->
                    <li><a href="index.php?p=contact">Contact</a></li>
                </ul>
            </nav>

            <!-- Connexion -->
            <div class="auth-button">
                <a href="/connexion">Connexion</a>
            </div>
        </div>
    </header>

    <?= $content; ?>

    <footer class="main-footer">
        <div class="container">
            <p>
                Contact : <a href="mailto:contact@ecoride.fr">contact@ecoride.fr</a>
                |
                <a href="index.php?p=legale">Mentions Légales</a>
            </p>
            <p>&copy; <?= date('Y'); ?> EcoRide. Tous droits réservés.</p>
        </div>
    </footer>
</body>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturage Écologique</title>
    <!-- Inclure le CSS général de l'application -->
    <link rel="stylesheet" href="css/app.css">
    <!-- Inclure le CSS spécifique aux covoiturages -->
    <link rel="stylesheet" href="css/style_covoiturage.css">
    <!-- Inclure le CSS spécifique au menu -->
    <link rel="stylesheet" href="css/style_menu.css">
    <!-- Inclure le CSS spécifique au contact -->
    <link rel="stylesheet" href="css/style_contact.css">
    <!-- Inclure le CSS spécifique aux mention légales -->
    <link rel="stylesheet" href="css/style_legal.css">
</head>


</html>