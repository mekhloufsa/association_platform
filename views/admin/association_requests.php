<div style="margin-bottom: 2rem;">
    <h1 class="gradient-text">Demandes de Création d'Association</h1>
    <p style="color: var(--text-muted);">Validez ou refusez les nouveaux projets d'association.</p>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <?php if(empty($requests)): ?>
        <p style="color: var(--text-muted); text-align: center;">Aucune demande en attente.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 1rem;">Demandeur</th>
                        <th style="padding: 1rem;">Association</th>
                        <th style="padding: 1rem;">Date</th>
                        <th style="padding: 1rem;">Statut</th>
                        <th style="padding: 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($requests as $r): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">
                                <div><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">N° CIN: <?= htmlspecialchars($r['national_id_number']) ?></div>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600;"><?= htmlspecialchars($r['name']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= substr(htmlspecialchars($r['description']), 0, 50) ?>...</div>
                            </td>
                            <td style="padding: 1rem; font-size: 0.9rem;"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                            <td style="padding: 1rem;">
                                <span class="badge badge-<?= $r['status'] === 'pending' ? 'warning' : ($r['status'] === 'approved' ? 'success' : 'danger') ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: right;">
                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association-request/<?= $r['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; margin-right: 0.5rem;">Voir détails</a>
                                <?php if($r['status'] === 'pending'): ?>
                                    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association-request/review" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Accepter</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Refuser</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
