<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Espace Administrateur Central</h1>
    <div style="display: flex; gap: 1rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/users" class="btn btn-secondary">Gérer les Utilisateurs</a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/associations" class="btn btn-primary">Gérer les Associations</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Utilisateurs Inscrits</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: var(--accent-color);"><?= $usersCount ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Associations Totales</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #10b981;"><?= $totalAssocs ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">En attente Validation</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #f59e0b;"><?= count($pendingAssocs) ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Dons Totaux (DZD)</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #10b981;"><?= number_format($totalDonations, 0, ',', ' ') ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Demandes en attente</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #6366f1;"><?= $pendingRequestsCount ?></span>
    </div>
</div>

<!-- Admin Quick Links -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/help-requests" class="glass-panel" style="padding: 1.5rem; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 2rem;">🆘</span>
        <div>
            <h3 style="margin-bottom: 0.25rem;">Demandes d'aide</h3>
        </div>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/donations" class="glass-panel" style="padding: 1.5rem; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 2rem;">💵</span>
        <div>
            <h3 style="margin-bottom: 0.25rem;">Dons Financiers</h3>
        </div>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/material-donations" class="glass-panel" style="padding: 1.5rem; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 2rem;">📦</span>
        <div>
            <h3 style="margin-bottom: 0.25rem;">Dons Matériels</h3>
        </div>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/campaigns" class="glass-panel" style="padding: 1.5rem; text-decoration: none; color: inherit; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 2rem;">🤝</span>
        <div>
            <h3 style="margin-bottom: 0.25rem;">Bénévolat</h3>
        </div>
    </a>
</div>

<div class="glass-panel" style="margin-top: 3rem; padding: 2rem;">
    <h2 style="margin-bottom: 1.5rem;">Associations en attente de validation</h2>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Nom</th>
                    <th style="padding: 1rem;">Président</th>
                    <th style="padding: 1rem;">Date de demande</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($pendingAssocs)): foreach($pendingAssocs as $assoc): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($assoc['name']) ?></div>
                        </td>
                        <td style="padding: 1rem; color: var(--text-muted);">
                            <?= htmlspecialchars($assoc['president_prenom'] . ' ' . $assoc['president_nom']) ?>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y', strtotime($assoc['created_at'])) ?>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/validate" method="POST" style="display: inline;">
                                <input type="hidden" name="assoc_id" value="<?= $assoc['id'] ?>">
                                <button type="submit" name="status" value="approved" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: #10b981;">Approuver</button>
                                <button type="submit" name="status" value="rejected" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: #ef4444; border: none;">Refuser</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune demande en attente.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
