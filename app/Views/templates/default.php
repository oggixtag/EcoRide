<?php
// Template pour tous les pages - layout par défaut
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Covoiturage Écologique</title>
    <!-- Inclure le CSS général de l'application -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_app.css">
    <!-- Inclure le CSS spécifique aux utilisateurs (authentification & dashboard) -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_utilisateur.css">
    <!-- Inclure le CSS spécifique aux covoiturages -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_covoiturage.css">
    <!-- Inclure le CSS spécifique au menu -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_menu.css">
    <!-- Inclure le CSS spécifique au contact -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_contact.css">
    <!-- Inclure le CSS spécifique aux mention légales -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_legale.css">
    <!-- Inclure le CSS spécifique à la page trajet -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_trajet.css">
    <!-- Inclure le CSS spécifique à la page détail du trajet -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_trajet_detail.css">
    <!-- Inclure le script spécifique à la page trajet -->
    <script src="/EcoRide/public/js/script_trajet.js"></script>
    <script src="/EcoRide/public/js/script_voiture.js"></script> <!-- Added for US8 -->
</head>

<body>

    <header class="main-header">
        <div class="container nav-content">
            <div class="logo">
                <a href="index.php">EcoRide 🌿</a>
            </div>

            <nav class="main-nav">
                <ul>
                    <!-- Retour vers la page d’accueil (toujours présent) -->
                    <li><a href="index.php?p=philosophie" class="<?= (isset($_GET['p']) && $_GET['p'] === 'philosophie') ? 'active' : '' ?>">Philosophie</a></li>

                    <!-- Accès aux covoiturages (affichage des voyage dans la base de donné) -->
                    <li><a href="index.php?p=covoiturage" class="<?= (isset($_GET['p']) && $_GET['p'] === 'covoiturage') ? 'active' : '' ?>">Covoiturages</a></li>

                    <!-- Contact -->
                    <li><a href="index.php?p=contact" class="<?= (isset($_GET['p']) && $_GET['p'] === 'contact') ? 'active' : '' ?>">Contact</a></li>
                </ul>
            </nav>

            <!-- Bouton d'authentification dynamique -->
            <div class="auth-button">
                <?php if (isset($_SESSION['auth']) && !empty($_SESSION['auth'])): ?>
                    <!-- Utilisateur authentifié -->
                    <a href="index.php?p=utilisateurs.index" class="btn-dashboard">Mon Dashboard</a>
                    <a href="index.php?p=logout" class="btn-logout">Déconnexion</a>
                <?php else: ?>
                    <!-- Utilisateur non authentifié -->
                    <a href="index.php?p=utilisateurs.inscrir" class="btn-login">S'inscrir</a> | <a href="index.php?p=utilisateurs.login" class="btn-login">Se connecter</a>
                <?php endif; ?>
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

</html>