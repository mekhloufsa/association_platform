<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 2rem;">Participer à un Siège</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Devenez responsable d'un bureau local pour une association dans votre wilaya.</p>

    <!-- Filtre par Wilaya -->
    <div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        <span style="color: var(--text-muted);">Filtrer par Wilaya :</span>
        <select onchange="window.location.href='?wilaya_id=' + this.value" class="glass-panel" style="padding: 0.5rem; background: rgba(255,255,255,0.1); color: white; border: 1px solid var(--glass-border);">
            <?php foreach($wilayas as $w): ?>
                <option value="<?= $w['id'] ?>" <?= $w['id'] == $current_wilaya ? 'selected' : '' ?>><?= $w['id'] ?> - <?= htmlspecialchars($w['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if(empty($sieges)): ?>
        <div class="glass-panel" style="padding: 3rem; text-align: center; color: var(--text-muted);">
            Il n'y a aucun siège vacant dans cette wilaya actuellement.
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            <?php foreach($sieges as $s): ?>
                <div class="glass-panel" style="padding: 1.5rem;">
                    <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($s['association_name']) ?></h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($s['address']) ?>
                    </p>
                    
                    <button onclick="document.getElementById('form-<?= $s['id'] ?>').style.display='block'; this.style.display='none'" class="btn btn-primary" style="width: 100%;">Postuler pour ce siège</button>

                    <div id="form-<?= $s['id'] ?>" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--glass-border);">
                        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/request/siege-apply" method="POST">
                            <input type="hidden" name="siege_id" value="<?= $s['id'] ?>">
                            
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; margin-bottom: 0.3rem;">N° Carte Nationale</label>
                                <input type="text" name="national_id_number" required class="glass-panel" style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--glass-border);">
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; margin-bottom: 0.3rem;">Pourquoi voulez-vous ce rôle ?</label>
                                <textarea name="description" required class="glass-panel" style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--glass-border);" rows="3"></textarea>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; font-size: 0.8rem; margin-bottom: 0.3rem;">Informations de contact (Tél, etc.)</label>
                                <input type="text" name="contact_info" required class="glass-panel" style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--glass-border);">
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">Envoyer la candidature</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
