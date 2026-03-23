<div style="margin-bottom: 2rem;">
    <div style="display: flex; align-items: center; gap: 2rem;">
        <?php if($association['logo_path']): ?>
            <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= $association['logo_path'] ?>" style="width: 120px; height: 120px; border-radius: 20%; object-fit: cover; border: 2px solid var(--glass-border);">
        <?php else: ?>
            <div style="width: 120px; height: 120px; border-radius: 20%; background: var(--accent-color); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                <?= substr($association['name'], 0, 1) ?>
            </div>
        <?php endif; ?>
        <div>
            <h1 class="gradient-text" style="font-size: 2.5rem;"><?= htmlspecialchars($association['name']) ?></h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;"><?= htmlspecialchars($association['description']) ?></p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 3rem;">
    <div>
        <h2 style="margin-bottom: 1.5rem;">Antennes Locales (Sièges)</h2>
        <?php if(empty($sieges)): ?>
            <div class="glass-panel" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                Cette association n'a pas encore de sièges actifs affichés.
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <?php foreach($sieges as $s): ?>
                    <div class="glass-panel" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin-bottom: 0.25rem;">Wilaya de <?= htmlspecialchars($s['wilaya_name']) ?></h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem;"><?= htmlspecialchars($s['address']) ?></p>
                            <div style="font-size: 0.8rem; color: var(--accent-color); margin-top: 0.5rem;">
                                Responsable: <?= htmlspecialchars($s['manager_prenom'] . ' ' . $s['manager_nom']) ?>
                            </div>
                        </div>
                        <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/help-request?association_id=<?= $association['id'] ?>&siege_id=<?= $s['id'] ?>" class="btn btn-primary">Contacter ce siège</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="grid-column: 1 / -1; margin-top: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Campagnes en cours</h2>
        <?php if(empty($campaigns)): ?>
            <div class="glass-panel" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                Aucune campagne active pour le moment.
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                <?php foreach($campaigns as $camp): ?>
                    <div class="glass-panel" style="padding: 1.5rem; display: flex; flex-direction: column;">
                        <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($camp['title']) ?> <span class="badge badge-secondary" style="font-size: 0.6rem; vertical-align: middle;"><?= ucfirst($camp['campaign_type'] ?? 'local') ?></span></h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem; flex: 1;"><?= htmlspecialchars(substr($camp['description'], 0, 100)) ?>...</p>
                        
                        <!-- Objectif / Besoin progress bar -->
                        <div style="margin-top: 1rem; margin-bottom: 1.5rem;">
                            <?php 
                                $progressPercent = 0;
                                $goalText = '';
                                if (($camp['need_type'] ?? 'personnel') === 'personnel') {
                                    if ($camp['max_volunteers'] > 0) {
                                        $progressPercent = min(100, round(($camp['current_volunteers'] / $camp['max_volunteers']) * 100));
                                        $goalText = intval($camp['current_volunteers']) . ' / ' . intval($camp['max_volunteers']) . ' bénévoles';
                                    } else {
                                        $progressPercent = 100;
                                        $goalText = intval($camp['current_volunteers']) . ' bénévoles (Ouvert)';
                                    }
                                } else {
                                    if ($camp['financial_goal'] > 0) {
                                        $progressPercent = min(100, round(($camp['current_raised'] / $camp['financial_goal']) * 100));
                                        $goalText = number_format($camp['current_raised'], 0, '', ' ') . ' / ' . number_format($camp['financial_goal'], 0, '', ' ') . ' DZD';
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
                            <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                                <div style="height: 100%; width: <?= $progressPercent ?>%; background: <?= ($camp['need_type'] ?? 'personnel') === 'personnel' ? 'var(--accent-color)' : '#10b981' ?>;"></div>
                            </div>
                        </div>

                        <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'user'): ?>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/campaigns" class="btn btn-secondary" style="width: 100%; text-align: center;">Participer</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3>Actions Rapides</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem;">
                <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/donation?association_id=<?= $association['id'] ?>" class="btn btn-primary" style="width: 100%; text-align: center;">Faire un don direct</a>
                    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/help-request?association_id=<?= $association['id'] ?>" class="btn btn-secondary" style="width: 100%; text-align: center;">Demander de l'aide</a>
                <?php else: ?>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Les actions citoyennes sont désactivées pour les administrateurs.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
