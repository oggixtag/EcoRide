<h1>Tableau de bord - Employé</h1>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h4>Avis en attente de validation</h4>
            </div>
            <div class="card-body">
                <?php if (empty($avis_pending)): ?>
                    <p>Aucun avis en attente.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pseudo</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($avis_pending as $avis): ?>
                            <tr>
                                <td><?= htmlspecialchars($avis->pseudo); ?></td>
                                <td><?= htmlspecialchars($avis->note); ?></td>
                                <td><?= htmlspecialchars($avis->commentaire); ?></td>
                                <td>
                                    <form action="index.php?p=admin.employe.validateAvis" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $avis->avis_id; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                    </form>
                                    <form action="index.php?p=admin.employe.refuseAvis" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $avis->avis_id; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4>Covoiturages signalés ("S'est mal passé")</h4>
            </div>
            <div class="card-body">
                <?php if (empty($bad_carpools)): ?>
                    <p>Aucun covoiturage signalé.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Départ</th>
                                <th>Date</th>
                                <th>Arrivée</th>
                                <th>Date A.</th>
                                <th>Conducteur</th>
                                <th>Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bad_carpools as $trip): ?>
                            <tr>
                                <td><?= $trip->covoiturage_id; ?></td>
                                <td><?= htmlspecialchars($trip->lieu_depart); ?></td>
                                <td><?= htmlspecialchars($trip->date_depart); ?></td>
                                <td><?= htmlspecialchars($trip->lieu_arrivee); ?></td>
                                <td><?= htmlspecialchars($trip->date_arrivee); ?></td>
                                <td>
                                    <?= htmlspecialchars($trip->driver_pseudo); ?><br>
                                    <small>(<?= htmlspecialchars($trip->driver_email); ?>)</small>
                                </td>
                                <td>
                                    <span class="badge badge-warning"><?= htmlspecialchars($trip->motif); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


