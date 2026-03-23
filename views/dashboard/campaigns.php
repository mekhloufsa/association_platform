<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
    <a href="<?= $basePath ?>/dashboard" style="color: var(--accent-color); text-decoration: none; display: inline-block; margin-bottom: 2rem;">← Retour au tableau de bord</a>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h1 class="gradient-text">Campagnes de Bénévolat</h1>
            <p style="color: var(--text-muted);">Rejoignez une action solidaire près de chez vous.</p>
        </div>
    </div>

    <div class="feature-grid">
        <?php if(!empty($campaigns)): foreach($campaigns as $camp): ?>
            <div class="feature-card glass-panel" style="display: flex; flex-direction: column;">
                <div style="flex: 1;">
                    <span style="font-size: 0.75rem; color: var(--accent-color); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        <?= htmlspecialchars($camp['association_name']) ?>
                    </span>
                    <h3 style="margin: 0.5rem 0 1rem;"><?= htmlspecialchars($camp['title']) ?></h3>
                    <p style="font-size: 0.9rem; margin-bottom: 1.5rem;"><?= htmlspecialchars($camp['description']) ?></p>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span>📅 Du <?= date('d/m/Y', strtotime($camp['start_date'])) ?> au <?= date('d/m/Y', strtotime($camp['end_date'])) ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span>📍 <?= htmlspecialchars($camp['location'] ?? 'Non spécifié') ?></span>
                            <span class="badge badge-secondary" style="font-size: 0.65rem; margin-left: 0.5rem;"><?= ucfirst($camp['campaign_type'] ?? 'local') ?></span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem;">
                        <?php 
                            $progressPercent = 0;
                            $goalText = '';
                            if (($camp['need_type'] ?? 'personnel') === 'personnel') {
                                if ($camp['max_volunteers'] > 0) {
                                    $progressPercent = min(100, round(($camp['current_volunteers'] / $camp['max_volunteers']) * 100));
                                    $goalText = intval($camp['current_volunteers']) . ' / ' . intval($camp['max_volunteers']) . ' bénévoles inscrits';
                                } else {
                                    $progressPercent = 100; // undefined max -> visually full or something else
                                    $goalText = intval($camp['current_volunteers']) . ' bénévoles inscrits (Objectif ouvert)';
                                }
                            } else {
                                if ($camp['financial_goal'] > 0) {
                                    $progressPercent = min(100, round(($camp['current_raised'] / $camp['financial_goal']) * 100));
                                    $goalText = number_format($camp['current_raised'], 0, '', ' ') . ' / ' . number_format($camp['financial_goal'], 0, '', ' ') . ' DZD collectés';
                                } else {
                                    $progressPercent = 0;
                                    $goalText = 'Objectif financier non défini';
                                }
                            }
                        ?>
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.3rem; font-weight: 600;">
                            <span style="color: var(--text-muted);"><?= $goalText ?></span>
                            <span style="color: <?= ($camp['need_type'] ?? 'personnel') === 'personnel' ? 'var(--accent-color)' : '#10b981' ?>"><?= $progressPercent ?>%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: <?= $progressPercent ?>%; background: <?= ($camp['need_type'] ?? 'personnel') === 'personnel' ? 'var(--accent-color)' : '#10b981' ?>; transition: width 0.5s ease;"></div>
                        </div>
                    </div>
                </div>
                
                <form action="<?= $basePath ?>/dashboard/register-volunteer" method="POST" style="margin-top: 1.5rem;">
                    <input type="hidden" name="campaign_id" value="<?= $camp['id'] ?>">
                    <?php if(($camp['need_type'] ?? 'personnel') === 'personnel'): ?>
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Devenir bénévole</button>
                    <?php else: ?>
                        <a href="<?= $basePath ?>/dashboard/donation?association_id=<?= $camp['association_id'] ?>" class="btn btn-primary" style="width: 100%; text-align: center; display: block;">Faire un don financier</a>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; else: ?>
            <div class="glass-panel" style="grid-column: 1 / -1; padding: 4rem; text-align: center;">
                <p style="color: var(--text-muted);">Aucune campagne de bénévolat n'est ouverte pour le moment. Revenez bientôt !</p>
            </div>
        <?php endif; ?>
    </div>
</div>
