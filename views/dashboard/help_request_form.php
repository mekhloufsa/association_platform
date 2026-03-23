<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <a href="<?= $basePath ?>/dashboard" style="color: var(--accent-color); text-decoration: none; display: inline-block; margin-bottom: 2rem;">← Retour au tableau de bord</a>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <h1 class="gradient-text" style="margin-bottom: 1.5rem;">Faire une demande d'aide</h1>
        <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Expliquez-nous votre situation. Vous pouvez choisir une association spécifique ou laisser l'administration diriger votre demande.</p>

        <form action="<?= $basePath ?>/dashboard/help-request" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-group">
                <label for="siege_id" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Siège Local ciblée (Obligatoire)</label>
                <select id="siege_id" name="siege_id" required style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                    <option value="">Sélectionner un bureau local...</option>
                    <?php foreach($associations as $assoc): ?>
                        <optgroup label="<?= htmlspecialchars($assoc['name']) ?>">
                            <?php foreach($assoc['sieges'] as $s): ?>
                                <option value="<?= $s['id'] ?>">Bureau de <?= htmlspecialchars($s['wilaya_name']) ?> (<?= htmlspecialchars($s['address']) ?>)</option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Les demandes d'aide sont gérées par les bureaux locaux uniquement.</p>
            </div>

            <div class="form-group">
                <label for="subject" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Sujet de la demande</label>
                <input type="text" id="subject" name="subject" required placeholder="Ex: Aide alimentaire, Accompagnement médical..." 
                       style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div class="form-group">
                <label for="description" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description détaillée</label>
                <textarea id="description" name="description" required rows="6" placeholder="Décrivez votre besoin en détail..."
                          style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; resize: vertical;"></textarea>
            </div>

            <div class="form-group">
                <label for="files" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Pièces jointes (Justificatifs, CNI, etc.)</label>
                <input type="file" id="files" name="files[]" multiple style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Vous pouvez sélectionner plusieurs fichiers.</p>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Envoyer votre demande</button>
        </form>
    </div>
</div>
