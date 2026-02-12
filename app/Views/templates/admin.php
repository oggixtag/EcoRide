<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Administration</title>
    <!-- Inclure le CSS général de l'application -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_app.css">
    <!-- Inclure le CSS spécifique à l'admin -->
    <link rel="stylesheet" href="/EcoRide/public/css/style_admin.css">
</head>
<body>

    <nav class="navbar">
        <a class="navbar-brand" href="index.php?p=admin.employes.dashboard">EcoRide Admin</a>
        <div class="navbar-nav">
            <?php if (isset($_SESSION['auth_admin'])): ?>
                <a href="index.php?p=admin.dashboard">Tableau de bord</a>
            <?php elseif (isset($_SESSION['auth_employe'])): ?>
                <a href="index.php?p=admin.employes.dashboard">Tableau de bord</a>
            <?php endif; ?>
            
            <a href="index.php?p=home">Retour au Site</a>

            <?php if (isset($_SESSION['auth_admin'])): ?>
                <a href="index.php?p=admin.logout">Déconnexion</a>
            <?php elseif (isset($_SESSION['auth_employe'])): ?>
                <a href="index.php?p=admin.employes.logout">Déconnexion</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container-admin">
        <?= $content; ?>
    </div>

</body>
</html>
