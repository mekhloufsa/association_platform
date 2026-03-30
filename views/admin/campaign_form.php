<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text"><?= isset($campaign) ? 'Modifier la Campagne' : 'Nouvelle Campagne' ?></h1>
        <p style="color: var(--text-muted);">Gestion des campagnes de bénévolat et de collecte.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaigns" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="glass-panel" style="padding: 2.5rem; max-width: 900px;">
    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaign/save" method="POST" enctype="multipart/form-data">
        <?php if(isset($campaign)): ?>
            <input type="hidden" name="id" value="<?= $campaign['id'] ?>">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Titre de la campagne</label>
                <input type="text" name="title" value="<?= $campaign['title'] ?? '' ?>" required class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Lieu / Ville</label>
                <input type="text" name="location" value="<?= $campaign['location'] ?? '' ?>" required class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description détaillée</label>
            <textarea name="description" rows="6" required class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white; resize: vertical;"><?= $campaign['description'] ?? '' ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Date de début</label>
                <input type="date" name="start_date" value="<?= $campaign['start_date'] ?? '' ?>" required class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Date de fin</label>
                <input type="date" name="end_date" value="<?= $campaign['end_date'] ?? '' ?>" required class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Type de besoin</label>
                <select name="need_type" class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white; background: rgba(0,0,0,0.5);">
                    <option value="personnel" <?= (isset($campaign) && $campaign['need_type'] === 'personnel') ? 'selected' : '' ?>>Bénévolat (Humain)</option>
                    <option value="financial" <?= (isset($campaign) && $campaign['need_type'] === 'financial') ? 'selected' : '' ?>>Dons (Financier)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Objectif (Nombre ou Montant)</label>
                <input type="number" name="<?= (isset($campaign) && $campaign['need_type'] === 'financial') ? 'financial_goal' : 'max_volunteers' ?>" 
                       value="<?= (isset($campaign) && $campaign['need_type'] === 'financial') ? $campaign['financial_goal'] : ($campaign['max_volunteers'] ?? '') ?>" 
                       class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Type de campagne</label>
                <select name="campaign_type" class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white; background: rgba(0,0,0,0.5);">
                    <option value="local" <?= (isset($campaign) && $campaign['campaign_type'] === 'local') ? 'selected' : '' ?>>Locale (Siège)</option>
                    <option value="national" <?= (isset($campaign) && $campaign['campaign_type'] === 'national') ? 'selected' : '' ?>>Nationale (Association)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Statut</label>
                <select name="status" class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white; background: rgba(0,0,0,0.5);">
                    <option value="open" <?= (isset($campaign) && $campaign['status'] === 'open') ? 'selected' : '' ?>>Ouverte</option>
                    <option value="closed" <?= (isset($campaign) && $campaign['status'] === 'closed') ? 'selected' : '' ?>>Fermée</option>
                    <option value="finished" <?= (isset($campaign) && $campaign['status'] === 'finished') ? 'selected' : '' ?>>Terminée (Historique)</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 3rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Image de la campagne (Affiche)</label>
            <?php if(isset($campaign) && $campaign['image_path']): ?>
                <div style="margin-bottom: 1rem;">
                    <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($campaign['image_path']) ?>" style="width: 200px; border-radius: 8px; border: 1px solid var(--glass-border);">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="glass-panel" style="width: 100%; padding: 0.8rem; border-radius: 8px; color: white;">
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">Enregistrer les modifications</button>
        </div>
    </form>
</div>
