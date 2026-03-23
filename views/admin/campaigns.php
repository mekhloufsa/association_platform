<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Toutes les Campagnes de Bénévolat</h1>
        <p style="color: var(--text-muted);">Vue globale des compagnes de volontariat associatives.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Campagne</th>
                    <th style="padding: 1rem;">Association</th>
                    <th style="padding: 1rem;">Période & Type</th>
                    <th style="padding: 1rem;">Objectif</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($campaigns)): foreach($campaigns as $c): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; font-size: 1.05rem;"><?= htmlspecialchars($c['title']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                <?= htmlspecialchars(substr($c['description'], 0, 50)) ?>...
                            </div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.95rem; font-weight: 500;">
                            <?= htmlspecialchars($c['association_name']) ?>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <div style="margin-bottom: 0.3rem;">
                                <?= date('d/m/Y', strtotime($c['start_date'])) ?> - <?= date('d/m/Y', strtotime($c['end_date'])) ?>
                            </div>
                            <span class="badge badge-secondary" style="font-size: 0.7rem;"><?= ucfirst($c['campaign_type']) ?></span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?php if ($c['need_type'] === 'personnel'): ?>
                                <span style="color: var(--accent-color); font-weight: 600;"><?= intval($c['current_volunteers']) ?> / <?= intval($c['max_volunteers']) ?></span> Bénévoles
                            <?php else: ?>
                                <span style="color: #10b981; font-weight: 600;"><?= number_format($c['current_raised'], 0, '', ' ') ?> / <?= number_format($c['financial_goal'], 0, '', ' ') ?> </span> DZD
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusBadge = 'success';
                                $statusText = 'Ouverte';
                                if($c['status'] === 'closed') { $statusBadge = 'warning'; $statusText = 'Fermée'; }
                                if($c['status'] === 'finished') { $statusBadge = 'secondary'; $statusText = 'Terminée'; }
                            ?>
                            <span class="badge badge-<?= $statusBadge ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune campagne de bénévolat enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
