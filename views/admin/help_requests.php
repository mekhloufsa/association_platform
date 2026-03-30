<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Toutes les Demandes d'Aide</h1>
    <a href="<?= $basePath ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Citoyen</th>
                    <th style="padding: 1rem;">Association</th>
                    <th style="padding: 1rem;">Sujet</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($requests)): foreach($requests as $req): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y', strtotime($req['created_at'])) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($req['prenom'] . ' ' . $req['nom']) ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?= htmlspecialchars($req['association_name'] ?? 'Don général') ?>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?= htmlspecialchars($req['subject']) ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusColor = '#f59e0b';
                                if($req['status'] === 'accepted') $statusColor = '#10b981';
                                if($req['status'] === 'rejected') $statusColor = '#ef4444';
                            ?>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                <?= ucfirst($req['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <a href="<?= $basePath ?>/admin/help-request/<?= $req['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Détails</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune demande d'aide sur la plateforme.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
