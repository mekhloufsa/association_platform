<?php
$isEdit = isset($annonce) && !empty($annonce);
?>
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 2rem;"><?= $isEdit ? "Modifier l'Annonce" : "Créer une Annonce" ?></h1>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/add-annonce" method="POST" enctype="multipart/form-data">
            <?php if($isEdit): ?>
                <input type="hidden" name="id" value="<?= $annonce['id'] ?>">
            <?php endif; ?>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Titre de l'annonce</label>
                <input type="text" name="title" value="<?= $isEdit ? htmlspecialchars($annonce['title']) : '' ?>" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Contenu</label>
                <textarea name="content" rows="6" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;"><?= $isEdit ? htmlspecialchars($annonce['content']) : '' ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Visibilité</label>
                    <select name="visibility" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        <option value="public" <?= ($isEdit && $annonce['visibility'] === 'public') ? 'selected' : '' ?> style="color: black;">Public (Tout le monde)</option>
                        <option value="users_only" <?= ($isEdit && $annonce['visibility'] === 'users_only') ? 'selected' : '' ?> style="color: black;">Utilisateurs Connectés Uniquement</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Statut</label>
                    <select name="status" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        <option value="published" <?= ($isEdit && $annonce['status'] === 'published') ? 'selected' : '' ?> style="color: black;">Publié directement</option>
                        <option value="draft" <?= ($isEdit && $annonce['status'] === 'draft') ? 'selected' : '' ?> style="color: black;">Brouillon</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Image à la une (Optionnel)</label>
                <?php if($isEdit && !empty($annonce['image_path'])): ?>
                    <div style="margin-bottom: 0.5rem; font-size: 0.85rem; color: var(--accent-color);">Image actuelle : <?= htmlspecialchars(basename($annonce['image_path'])) ?></div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div style="margin-bottom: 2.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Pièce Jointe (Optionnel - PDF, DOCX...)</label>
                <?php if($isEdit && !empty($annonce['attachment_path'])): ?>
                    <div style="margin-bottom: 0.5rem; font-size: 0.85rem; color: var(--accent-color);">Fichier actuel : <?= htmlspecialchars(basename($annonce['attachment_path'])) ?></div>
                <?php endif; ?>
                <input type="file" name="attachment" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/annonces" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary"><?= $isEdit ? "Enregistrer les modifications" : "Publier l'Annonce" ?></button>
            </div>
        </form>
    </div>
</div>
