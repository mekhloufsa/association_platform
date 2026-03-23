<div class="annonces-container">
    <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 2rem; text-align: center;">Actualités & Annonces</h1>

    <div class="feature-grid">
        <?php if (!empty($annonces)): foreach ($annonces as $annonce): ?>
            <div class="feature-card glass-panel" style="overflow: hidden; padding: 0;">
                <div class="annonce-img" style="height: 200px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                    <?php if($annonce['image_url']): ?>
                        <img src="<?= htmlspecialchars($annonce['image_url']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Image indisponible</span>
                    <?php endif; ?>
                </div>
                <div style="padding: 1.5rem;">
                    <span style="font-size: 0.75rem; color: var(--accent-color); font-weight: 600; text-transform: uppercase;"><?= date('d M Y', strtotime($annonce['published_at'])) ?></span>
                    <h3 style="margin: 0.5rem 0;"><?= htmlspecialchars($annonce['title']) ?></h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                        <?= htmlspecialchars(substr($annonce['content'], 0, 150)) ?>...
                    </p>
                    <a href="#" class="gradient-text" style="font-weight: 600; font-size: 0.9rem;">Lire la suite →</a>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="glass-panel" style="grid-column: 1 / -1; padding: 3rem; text-align: center;">
                <p>Aucune annonce publiée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
