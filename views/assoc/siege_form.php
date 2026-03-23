<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 2rem;">Ajouter un Siège</h1>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/add-siege" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Wilaya</label>
                <select name="wilaya_id" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    <option value="">Sélectionnez une wilaya</option>
                    <?php foreach($wilayas as $w): ?>
                        <option value="<?= $w['id'] ?>"><?= $w['id'] ?> - <?= htmlspecialchars($w['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Adresse précise</label>
                <textarea name="address" rows="3" required placeholder="Rue, quartier, code postal..." class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;"></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/sieges" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer le Siège</button>
            </div>
        </form>
    </div>
</div>
