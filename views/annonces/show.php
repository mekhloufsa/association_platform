<?php 
    $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
?>
<div class="annonces-container" style="max-width: 900px; margin: 0 auto; padding-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($annonce['title']) ?></h1>
            <div style="display: flex; gap: 1rem; align-items: center; color: var(--text-muted); font-size: 0.9rem;">
                <span>📅 <?= date('d M Y', strtotime($annonce['published_at'])) ?></span>
                <span>👤 <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?> (Admin)</span>
            </div>
        </div>
        <a href="<?= $basePath ?>/annonces" class="btn btn-secondary">Retour aux annonces</a>
    </div>

    <div class="glass-panel" style="padding: 2rem; overflow: hidden;">
        <?php if(!empty($annonce['image_path'])): ?>
            <div style="margin-bottom: 2.5rem; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border);">
                <img src="<?= $basePath ?>/<?= htmlspecialchars($annonce['image_path']) ?>" alt="Affiche de l'annonce" style="width: 100%; max-height: 500px; object-fit: contain; background: rgba(0,0,0,0.4); display: block;">
            </div>
        <?php endif; ?>

        <div style="font-size: 1.1rem; line-height: 1.8; color: var(--text-main); white-space: pre-wrap; margin-bottom: 3rem;">
            <?= nl2br(htmlspecialchars($annonce['content'])) ?>
        </div>

        <?php if(!empty($annonce['attachment_path'])): ?>
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--glass-border);">
                <h3 style="font-size: 1.1rem; color: var(--accent-color); margin-bottom: 1rem;">Document(s) joint(s)</h3>
                <a href="<?= $basePath ?>/<?= htmlspecialchars($annonce['attachment_path']) ?>" target="_blank" class="glass-panel" style="display: inline-flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; text-decoration: none; border: 1px solid var(--primary-color); transition: all 0.3s;">
                    <span style="font-size: 1.5rem;">📄</span>
                    <div>
                        <div style="color: white; font-weight: 600;">Télécharger / Visualiser</div>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">Fichier attaché à cette annonce</div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
