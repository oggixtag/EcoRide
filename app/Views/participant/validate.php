<section class="presentation-section">
    <div class="presentation-content">
        <h1>Validation du Covoiturage</h1>
        
        <h2><?= htmlspecialchars($covoiturage->lieu_depart) ?> → <?= htmlspecialchars($covoiturage->lieu_arrivee) ?></h2>
        <p>Date : <?= htmlspecialchars($covoiturage->date_depart) ?></p>
        <p>Conduit par : <?= htmlspecialchars($covoiturage->pseudo) ?></p>

        <form action="index.php?p=participant.submitValidation" method="POST" class="form-utilisateurs" style="max-width: 600px; margin: 20px auto;">
            <input type="hidden" name="covoiturage_id" value="<?= $covoiturage->covoiturage_id ?>">
            
            <div class="form-group">
                <label>Comment s'est passé le trajet ?</label>
                <div style="margin-top: 10px;">
                    <label style="margin-right: 20px;">
                        <input type="radio" name="avis_covoiturage_id" value="1" required onclick="toggleComment(false)"> 
                        S'est bien passé
                    </label>
                    <label>
                        <input type="radio" name="avis_covoiturage_id" value="2" required onclick="toggleComment(true)"> 
                        S'est mal passé
                    </label>
                </div>
            </div>

            <div class="form-group" id="comment-group" style="display: none;">
                <label for="commentaire">Commentaire (obligatoire si mal passé)</label>
                <textarea name="commentaire" id="commentaire" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </form>
    </div>
</section>

<script>
function toggleComment(show) {
    const commentGroup = document.getElementById('comment-group');
    const commentInput = document.getElementById('commentaire');
    
    if (show) {
        commentGroup.style.display = 'block';
        commentInput.required = true;
    } else {
        commentGroup.style.display = 'none';
        commentInput.required = false;
    }
}
</script>
