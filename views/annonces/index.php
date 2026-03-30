<div class="annonces-container">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text" style="margin-bottom: 0.5rem;">Actualités et Annonces</h1>
            <p style="color: var(--text-muted); margin: 0;">Tenez-vous informé des dernières nouveautés et opportunités.</p>
        </div>
        <div>
            <select id="feedFilter" class="glass-panel" style="padding: 0.5rem 1rem; border-radius: 8px; color: white; background: rgba(255,255,255,0.1); border: 1px solid var(--glass-border);" onchange="filterFeed(this.value)">
                <option style="color: black;" value="all">Tout afficher</option>
                <option style="color: black;" value="annonce">Actualités Générales</option>
                <option style="color: black;" value="campaign">Campagnes de Bénévolat / Dons</option>
            </select>
        </div>
    </div>

    <div class="feature-grid">
        <?php if (!empty($annonces)): foreach ($annonces as $annonce): ?>
            <div class="feature-card glass-panel" style="overflow: hidden; padding: 0;" data-type="<?= $annonce['item_type'] ?>">
                <div class="annonce-img" style="height: 200px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                    <?php if(!empty($annonce['image_path'])): ?>
                        <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($annonce['image_path']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Image indisponible</span>
                    <?php endif; ?>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <span style="font-size: 0.75rem; color: var(--accent-color); font-weight: 600; text-transform: uppercase;">
                            <?= date('d M Y', strtotime($annonce['display_date'])) ?>
                        </span>
                        <?php if($annonce['visibility'] === 'users_only'): ?>
                            <span class="badge badge-secondary" style="font-size: 0.6rem;">Membres</span>
                        <?php endif; ?>
                    </div>
                    
                    <h2 style="font-size: 1.4rem; color: var(--text-main); margin-bottom: 0.5rem;">
                        <?php if($annonce['item_type'] === 'campaign'): ?>
                            <span style="color: #10b981; font-size: 0.8rem; border: 1px solid #10b981; padding: 0.2rem 0.6rem; border-radius: 12px; vertical-align: middle; margin-right: 0.5rem;">Campagne <?= $annonce['need_type'] === 'personnel' ? 'Bénévoles' : 'Collecte' ?></span>
                        <?php endif; ?>
                        <?= htmlspecialchars($annonce['title']) ?>
                    </h2>
                    
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars(strip_tags($annonce['content'])) ?>
                    </p>

                    <?php if($annonce['item_type'] === 'campaign'): ?>
                        <?php
                            $pct = 0;
                            if($annonce['need_type'] === 'personnel' && $annonce['max_volunteers']) {
                                $pct = min(100, round(($annonce['current_volunteers'] / $annonce['max_volunteers']) * 100));
                                $pctText = "{$annonce['current_volunteers']} inscrits sur {$annonce['max_volunteers']}";
                            } else if($annonce['need_type'] === 'financial' && $annonce['financial_goal']) {
                                $current = $annonce['current_raised'] ?? 0;
                                $pct = min(100, round(($current / $annonce['financial_goal']) * 100));
                                $pctText = number_format($current, 0, '', ' ') . " DZD récoltés sur " . number_format($annonce['financial_goal'], 0, '', ' ') . " DZD";
                            }
                        ?>
                        <?php if(isset($pctText)): ?>
                            <div style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.3rem;">
                                    <span style="color: var(--text-muted);">Progression vers l'objectif</span>
                                    <span style="color: white; font-weight: bold;"><?= $pct ?>%</span>
                                </div>
                                <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                                    <div style="width: <?= $pct ?>%; height: 100%; background: var(--accent-color); border-radius: 4px;"></div>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-align: right; margin-top: 0.3rem;"><?= $pctText ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                        <span class="gradient-text" style="font-weight: 600; font-size: 0.9rem;">Par <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?></span>
                        <?php if($annonce['item_type'] === 'annonce'): ?>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/annonce/<?= $annonce['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border: 1px dashed var(--accent-color);">Lire la suite →</a>
                        <?php else: ?>
                            <?php if(isset($annonce['already_participated']) && $annonce['already_participated']): ?>
                                <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981;" disabled>Déjà participé</button>
                            <?php endif; ?>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/campaign/<?= $annonce['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Détails →</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="glass-panel" style="grid-column: 1 / -1; padding: 3rem; text-align: center;">
                <p>Aucune annonce publiée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterFeed(type) {
    const items = document.querySelectorAll('.feed-item');
    items.forEach(item => {
        if (type === 'all' || item.getAttribute('data-type') === type) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
