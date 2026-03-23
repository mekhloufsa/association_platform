<div style="margin-bottom: 2rem;">
    <h1 class="gradient-text">Modifier le Siège</h1>
    <p style="color: var(--text-muted);">Mettez à jour les informations et assignez un responsable local.</p>
</div>

<div class="glass-panel" style="padding: 2rem; max-width: 600px;">
    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/update" method="POST">
        <input type="hidden" name="id" value="<?= $siege['id'] ?>">

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main);">Wilaya</label>
            <select name="wilaya_id" class="glass-panel" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);" required>
                <?php foreach($wilayas as $w): ?>
                    <option value="<?= $w['id'] ?>" <?= $w['id'] == $siege['wilaya_id'] ? 'selected' : '' ?>><?= htmlspecialchars($w['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main);">Adresse</label>
            <textarea name="address" class="glass-panel" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);" rows="3" required><?= htmlspecialchars($siege['address']) ?></textarea>
        </div>



        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Enregistrer les modifications</button>
            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/sieges" class="btn btn-secondary" style="flex: 1; text-align: center; text-decoration: none;">Annuler</a>
        </div>
    </form>
</div>
