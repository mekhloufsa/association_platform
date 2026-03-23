<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Tous les Dons Matériels</h1>
        <p style="color: var(--text-muted);">Vue globale de tous les dons en nature de la plateforme.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Date & Citoyen</th>
                    <th style="padding: 1rem;">Donation (Catégorie & Quantité)</th>
                    <th style="padding: 1rem;">Association (Siège)</th>
                    <th style="padding: 1rem;">Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($donations)): foreach($donations as $d): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-secondary" style="margin-bottom: 0.3rem; display: inline-block;"><?= htmlspecialchars($d['category']) ?></span>
                            <div style="font-size: 0.9rem; font-weight: 500;"><?= htmlspecialchars($d['quantity'] ?? 'N/A') ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($d['description']) ?>">
                                <?= htmlspecialchars($d['description']) ?>
                            </div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <div style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($d['association_name'] ?? 'National') ?></div>
                            <?php if ($d['siege_address']): ?>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars(substr($d['siege_address'], 0, 30)) ?>...</div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusBadge = 'warning'; // pending
                                $statusText = 'En attente';
                                if($d['status'] === 'scheduled') { $statusBadge = 'info'; $statusText = 'Planifié'; }
                                if($d['status'] === 'collected') { $statusBadge = 'success'; $statusText = 'Collecté'; }
                                if($d['status'] === 'cancelled') { $statusBadge = 'danger'; $statusText = 'Annulé'; }
                            ?>
                            <span class="badge badge-<?= $statusBadge ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun don matériel enregistré sur la plateforme.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
