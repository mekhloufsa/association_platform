<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Toutes les Campagnes de Bénévolat</h1>
        <p style="color: var(--text-muted);">Vue globale des compagnes de volontariat associatives.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<?php if(isset($_SESSION['flash_success'])): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

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
                    <th style="padding: 1rem; text-align: right;">Actions</th>
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
                                if($c['status'] === 'finished') { $statusBadge = 'secondary'; $statusText = 'Historique (Terminée)'; }
                            ?>
                            <span class="badge badge-<?= $statusBadge ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaign/detail/<?= $c['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Détails</a>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaign/edit/<?= $c['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; color: var(--accent-color); border-color: var(--accent-color);">Modifier</a>
                            
                            <?php if($c['status'] !== 'finished'): ?>
                                <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaign/status" method="POST" style="margin:0;">
                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                    <input type="hidden" name="status" value="finished">
                                    <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: #10b981; border: none;" onclick="return confirm('Classer cette campagne dans l\'historique ?');">Terminer</button>
                                </form>
                            <?php endif; ?>
                            
                            <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaign/delete" method="POST" style="margin:0;">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; color: #ef4444; border-color: #ef4444;" onclick="return confirm('Confirmer la suppression définitive ?');">Supprimer</button>
                            </form>
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
