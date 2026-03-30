<div class="campaign-detail-container">
    <div style="margin-bottom: 2rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/annonces" class="btn btn-secondary" style="font-size: 0.9rem;">← Retour aux annonces</a>
    </div>

    <div class="glass-panel" style="padding: 0; overflow: hidden; margin-bottom: 3rem;">
        <div class="campaign-header" style="height: 400px; position: relative; background: rgba(0,0,0,0.3);">
            <?php if(!empty($campaign['image_path'])): ?>
                <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($campaign['image_path']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--glass-bg), rgba(255,255,255,0.05));">
                    <h1 style="color: var(--text-muted); opacity: 0.5;">Image de la Campagne</h1>
                </div>
            <?php endif; ?>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 3rem; background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                <div style="margin-bottom: 1rem;">
                    <span style="background: #10b981; color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                        <?= $campaign['need_type'] === 'personnel' ? 'Bénévolat' : 'Collecte de Fonds' ?>
                    </span>
                    <span style="margin-left: 1rem; color: rgba(255,255,255,0.8); font-size: 0.9rem;">
                        <i class="fas fa-calendar"></i> Du <?= date('d/m/Y', strtotime($campaign['start_date'])) ?> au <?= date('d/m/Y', strtotime($campaign['end_date'])) ?>
                    </span>
                </div>
                <h1 style="font-size: 3rem; color: white; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.3);"><?= htmlspecialchars($campaign['title']) ?></h1>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; padding: 3rem;">
            <div class="campaign-content">
                <h3 style="color: var(--accent-color); margin-bottom: 1rem; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Description de la mission</h3>
                <div style="color: var(--text-main); line-height: 1.8; font-size: 1.1rem; white-space: pre-line; margin-bottom: 3rem;">
                    <?= htmlspecialchars($campaign['description']) ?>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="info-card" style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--glass-border);">
                        <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.5rem;">Organisé par</div>
                        <div style="font-weight: 600; font-size: 1.2rem; color: white;"><?= htmlspecialchars($campaign['association_name']) ?></div>
                    </div>
                    <div class="info-card" style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--glass-border);">
                        <div style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.5rem;">Lieu</div>
                        <div style="font-weight: 600; font-size: 1.2rem; color: white;"><?= htmlspecialchars($campaign['location'] ?: 'Non spécifié') ?></div>
                    </div>
                </div>
            </div>

            <div class="campaign-sidebar">
                <div class="glass-panel" style="padding: 2rem; background: rgba(255,255,255,0.05); border: 1px solid var(--accent-color);">
                    <?php
                        $pct = 0;
                        if($campaign['need_type'] === 'personnel' && $campaign['max_volunteers']) {
                            $pct = min(100, round(($campaign['current_volunteers'] / $campaign['max_volunteers']) * 100));
                            $statLabel = "Bénévoles Inscrits";
                            $statValue = "{$campaign['current_volunteers']} / {$campaign['max_volunteers']}";
                        } else if($campaign['need_type'] === 'financial' && $campaign['financial_goal']) {
                            $current = $campaign['current_raised'] ?? 0;
                            $pct = min(100, round(($current / $campaign['financial_goal']) * 100));
                            $statLabel = "Fonds Récoltés";
                            $statValue = number_format($current, 0, '', ' ') . " / " . number_format($campaign['financial_goal'], 0, '', ' ') . " DZD";
                        }
                    ?>

                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;"><?= $statLabel ?></div>
                        <div style="font-size: 2rem; color: white; font-weight: 800;"><?= $statValue ?></div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.5rem;">
                            <span style="color: var(--text-muted);">Objectif</span>
                            <span style="color: var(--accent-color); font-weight: bold;"><?= $pct ?>%</span>
                        </div>
                        <div style="width: 100%; height: 12px; background: rgba(255,255,255,0.1); border-radius: 6px; overflow: hidden;">
                            <div style="width: <?= $pct ?>%; height: 100%; background: var(--accent-color); border-radius: 6px; box-shadow: 0 0 15px var(--accent-color);"></div>
                        </div>
                    </div>

                    <?php if($alreadyParticipated): ?>
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1.5rem; border-radius: 8px; text-align: center; font-weight: 600;">
                            ✓ Vous participez déjà à cette mission
                        </div>
                    <?php else: ?>
                        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/campaign/register/<?= $campaign['id'] ?>" method="POST">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; font-weight: bold; border-radius: 12px;">
                                <?= $campaign['need_type'] === 'personnel' ? 'Je souhaite participer' : 'Je souhaite contribuer' ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <p style="font-size: 0.75rem; color: var(--text-muted); text-align: center; margin-top: 1.5rem; line-height: 1.4;">
                        En cliquant sur le bouton, vous vous engagez à respecter les conditions de l'association et à être présent si votre participation est confirmée.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
