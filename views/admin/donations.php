<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Tous les Dons Financiers</h1>
        <p style="color: var(--text-muted);">Vue globale de tous les dons financiers de la plateforme.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Citoyen</th>
                    <th style="padding: 1rem;">Bénéficiaire (Association/Antenne)</th>
                    <th style="padding: 1rem;">Montant</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($donations)): foreach($donations as $d): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem; font-size: 0.9rem;"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <div style="color: var(--text-muted);"><?= htmlspecialchars($d['association_name'] ?? 'National') ?></div>
                            <?php if ($d['siege_address']): ?>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">( <?= htmlspecialchars($d['siege_address']) ?> )</div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem; font-weight: 600; color: var(--accent-color);">
                            <?= number_format($d['amount'], 0, ',', ' ') ?> DZD
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-<?= $d['status'] === 'completed' ? 'success' : 'warning' ?>">
                                <?= ucfirst($d['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun don financier enregistré sur la plateforme.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
