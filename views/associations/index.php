<div class="annuaire-container">
    <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 2rem; text-align: center;">Annuaire des Associations</h1>
    
    <div class="search-filter glass-panel" style="padding: 1.5rem; margin-bottom: 3rem; display: flex; gap: 1rem; align-items: center;">
        <input type="text" placeholder="Rechercher une association..." style="flex: 1; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
        <select name="wilaya" style="padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            <option value="">Toutes les Wilayas</option>
            <?php if(!empty($wilayas)): foreach($wilayas as $wilaya): ?>
                <option value="<?= $wilaya['id'] ?>"><?= $wilaya['id'] ?> - <?= htmlspecialchars($wilaya['name']) ?></option>
            <?php endforeach; endif; ?>
        </select>
        <button class="btn btn-primary">Rechercher</button>
    </div>

    <div class="feature-grid">
        <?php if (!empty($associations)): foreach ($associations as $assoc): ?>
            <div class="feature-card glass-panel" style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="assoc-logo" style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: bold; overflow: hidden; border: 2px solid var(--glass-border);">
                    <?php if (!empty($assoc['logo_path'])): ?>
                        <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($assoc['logo_path']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <?= substr($assoc['name'], 0, 1) ?>
                    <?php endif; ?>
                </div>
                <h3><?= htmlspecialchars($assoc['name']) ?></h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); flex: 1; line-height: 1.5;">
                    <?= nl2br(htmlspecialchars($assoc['description'])) ?>
                </p>
                <div class="assoc-meta" style="font-size: 0.85rem; border-top: 1px solid var(--glass-border); padding-top: 1rem; display: flex; justify-content: space-between; color: var(--text-muted);">
                    <span>Par: <?= htmlspecialchars($assoc['president_prenom']) ?></span>
                </div>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/association/<?= $assoc['slug'] ?>" class="btn btn-secondary" style="width: 100%;">Voir Profil</a>
            </div>
        <?php endforeach; else: ?>
            <div class="glass-panel" style="grid-column: 1 / -1; padding: 3rem; text-align: center;">
                <p>Aucune association trouvée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
