<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails de la Campagne de Bénévolat</h1>
        <p style="color: var(--text-muted);">Vue complète de la mission #<?= $campaign['id'] ?></p>
    </div>
    <a href="<?= $basePath ?>/admin/campaigns" class="btn btn-secondary">Retour aux campagnes</a>
</div>

<div class="glass-panel" style="padding: 2.5rem; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
        <div>
            <h2 style="margin: 0 0 0.5rem 0; color: var(--text-main); font-size: 2rem;"><?= htmlspecialchars($campaign['title']) ?></h2>
            <div style="color: var(--accent-color); font-weight: 500;">
                Organisée par <a href="<?= $basePath ?>/admin/association/<?= $campaign['association_id'] ?>" style="color: var(--primary-color); text-decoration: none;"><?= htmlspecialchars($campaign['association_name']) ?></a>
            </div>
        </div>
        <?php 
            $statusBadge = 'success'; $statusText = 'Ouverte';
            if($campaign['status'] === 'closed') { $statusBadge = 'warning'; $statusText = 'Fermée'; }
            if($campaign['status'] === 'finished') { $statusBadge = 'secondary'; $statusText = 'Terminée'; }
        ?>
        <span class="badge badge-<?= $statusBadge ?>" style="font-size: 1rem; padding: 0.5rem 1.2rem;">
            <?= $statusText ?>
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2.5rem; padding: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px solid var(--glass-border);">
        <div style="text-align: center; border-right: 1px solid var(--glass-border);">
            <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Période</div>
            <div style="font-weight: 600; color: white;">
                <?= date('d/m/Y', strtotime($campaign['start_date'])) ?> 
                <span style="color: var(--text-muted); margin: 0 0.5rem;">au</span> 
                <?= date('d/m/Y', strtotime($campaign['end_date'])) ?>
            </div>
        </div>
        <div style="text-align: center; border-right: 1px solid var(--glass-border);">
            <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Lieu / Wilaya</div>
            <div style="font-weight: 600; color: white;">
                <?= htmlspecialchars($campaign['location']) ?>
            </div>
        </div>
        <div style="text-align: center;">
            <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Portée</div>
            <div style="font-weight: 600; color: white;">
                <span class="badge badge-secondary"><?= ucfirst($campaign['campaign_type']) ?></span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
        <div>
            <h3 style="color: var(--accent-color); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Description de la mission</h3>
            <p style="white-space: pre-wrap; line-height: 1.6; color: var(--text-main); font-size: 1rem;">
                <?= htmlspecialchars($campaign['description']) ?>
            </p>
        </div>
        <div>
            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Objectif</h3>
            <?php if ($campaign['need_type'] === 'personnel'): ?>
                <div style="background: rgba(16,185,129,0.1); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid rgba(16,185,129,0.3);">
                    <div style="font-size: 3rem; font-weight: bold; color: #10b981; line-height: 1; margin-bottom: 0.5rem;">
                        <?= intval($campaign['current_volunteers']) ?> <span style="font-size: 1.5rem; color: var(--text-muted);">/ <?= intval($campaign['max_volunteers']) ?></span>
                    </div>
                    <div style="color: var(--text-main); font-weight: 500;">Bénévoles inscrits</div>
                </div>
            <?php else: ?>
                <div style="background: rgba(59,130,246,0.1); padding: 1.5rem; border-radius: 8px; text-align: center; border: 1px solid rgba(59,130,246,0.3);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #3b82f6; line-height: 1; margin-bottom: 0.5rem;">
                        <?= number_format($campaign['current_raised'], 0, '', ' ') ?> <span style="font-size: 1.2rem; color: var(--text-muted);">/ <?= number_format($campaign['financial_goal'], 0, '', ' ') ?></span>
                    </div>
                    <div style="color: var(--text-main); font-weight: 500;">DZD Récoltés</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<h2 style="margin-bottom: 1.5rem; color: white;">Bénévoles Inscrits (<?= count($volunteers) ?>)</h2>
<div class="glass-panel" style="padding: 1rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Date d'inscription</th>
                    <th style="padding: 1rem;">Citoyen Bénévole</th>
                    <th style="padding: 1rem;">Contact</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($volunteers)): foreach($volunteers as $vol): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y H:i', strtotime($vol['registered_at'])) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <a href="<?= $basePath ?>/admin/user/<?= $vol['user_id'] ?>" style="font-weight: 600; color: var(--accent-color); text-decoration: none;">
                                <?= htmlspecialchars($vol['prenom'] . ' ' . $vol['nom']) ?>
                            </a>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <div style="color: var(--text-main);"><?= htmlspecialchars($vol['email']) ?></div>
                            <div style="color: var(--text-muted);"><?= htmlspecialchars($vol['phone'] ?? 'Non fourni') ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $vStatusBadge = 'warning';
                                $vStatusText = 'Inscrit';
                                if($vol['status'] === 'confirmed') { $vStatusBadge = 'info'; $vStatusText = 'Confirmé'; }
                                if($vol['status'] === 'attended') { $vStatusBadge = 'success'; $vStatusText = 'A participé'; }
                                if($vol['status'] === 'cancelled' || $vol['status'] === 'absent') { $vStatusBadge = 'danger'; $vStatusText = ucfirst($vol['status']); }
                            ?>
                            <span class="badge badge-<?= $vStatusBadge ?>">
                                <?= $vStatusText ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun bénévole ne s'est encore inscrit.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
