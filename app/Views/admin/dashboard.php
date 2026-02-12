<div style="margin-bottom: 20px; overflow: auto;">
    <h1 style="float: left; margin: 0;">Tableau de Bord Administrateur</h1>
    <div style="float: right;">
        <a href="index.php?p=admin.employes.index" class="btn btn-primary">Gestion Employés</a>
        <a href="index.php?p=admin.index" class="btn btn-primary" style="margin-left: 10px;">Gestion Utilisateurs</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
         <div class="card text-white bg-success mb-3">
            <div class="card-header">Crédits Totaux</div>
            <div class="card-body">
                <h5 class="card-title"><?= $credits_total; ?> Crédits</h5>
                <p class="card-text">Gagnés par la plateforme.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h3>Covoiturages par jour</h3>
        <div style="height: 300px; width: 100%;">
            <canvas id="graphiqueCovoiturages"></canvas>
        </div>
    </div>
    <div class="col-md-12">
        <h3>Crédits par jour</h3>
        <div style="height: 300px; width: 100%;">
            <canvas id="graphiqueCredits"></canvas>
        </div>
    </div>
</div>

<!-- Passage des données au JS -->
<script>
    var donneesCovoiturages = <?= json_encode($covoiturages); ?>;
    var donneesCredits = <?= json_encode($credits_par_jour); ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/script_graphiques-admin.js"></script>
