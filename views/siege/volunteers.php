<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Bénévoles Locaux</h1>
        <p style="color: var(--text-muted);">Bénévoles de la Wilaya de <?= htmlspecialchars($siege['wilaya_name']) ?> inscrits à vos campagnes.</p>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Bénévole</th>
                    <th style="padding: 1rem;">Campagne</th>
                    <th style="padding: 1rem;">Date d'inscription</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($volunteers)): foreach($volunteers as $v): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($v['prenom'] . ' ' . $v['nom']) ?></div>
                        </td>
                        <td style="padding: 1rem;"><?= htmlspecialchars($v['campaign_title']) ?></td>
                        <td style="padding: 1rem; font-size: 0.9rem;"><?= date('d/m/Y', strtotime($v['registered_at'])) ?></td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-<?= $v['status'] === 'registered' ? 'warning' : 'success' ?>">
                                <?= ucfirst($v['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun bénévole inscrit dans votre wilaya pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
