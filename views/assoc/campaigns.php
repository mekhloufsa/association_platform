<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Gestion des Campagnes</h1>
        <p style="color: var(--text-muted);">Créez et gérez vos appels au bénévolat et au don.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/add-campaign" class="btn btn-primary">+ Nouvelle Campagne</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Information</th>
                    <th style="padding: 1rem;">Date Début</th>
                    <th style="padding: 1rem;">Statut & Objectif</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($campaigns)): foreach($campaigns as $camp): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600; font-size: 1.05rem;"><?= htmlspecialchars($camp['title']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                                <?= htmlspecialchars(substr($camp['description'], 0, 45)) ?>...
                            </div>
                        </td>
                        <td style="padding: 1rem; color: var(--text-color); font-size: 0.9rem;">
                            <div><span class="badge badge-secondary" style="font-size: 0.7rem;"><?= ucfirst($camp['campaign_type'] ?? 'local') ?></span></div>
                            <div style="margin-top: 0.3rem;"><strong style="color: var(--text-muted);">Lieu:</strong> <?= htmlspecialchars($camp['location']) ?></div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y', strtotime($camp['start_date'])) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusColor = '#f59e0b'; // open
                                if($camp['status'] === 'closed') $statusColor = '#ef4444';
                                if($camp['status'] === 'finished') $statusColor = '#10b981';
                            ?>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                    <?= ucfirst($camp['status']) ?>
                                </span>
                            </div>
                            <!-- Objectif / Besoin -->
                            <div style="font-size: 0.85rem; margin-top: 0.5rem;">
                                <?php if (($camp['need_type'] ?? 'personnel') === 'personnel'): ?>
                                    <span style="color: var(--accent-color); font-weight: 600;">Bénévoles:</span> 
                                    <?= intval($camp['max_volunteers']) > 0 ? intval($camp['max_volunteers']) : 'Illimité' ?> max
                                <?php else: ?>
                                    <span style="color: #10b981; font-weight: 600;">Financier:</span> 
                                    <?= number_format($camp['current_raised'] ?? 0, 0, '', ' ') ?> / <?= number_format($camp['financial_goal'] ?? 0, 0, '', ' ') ?> DZD
                                <?php endif; ?>
                            </div>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/campaign/<?= $camp['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Détails</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune campagne enregistrée pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
