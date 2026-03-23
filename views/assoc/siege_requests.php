<div style="margin-bottom: 2rem;">
    <h1 class="gradient-text">Candidatures pour vos Sièges</h1>
    <p style="color: var(--text-muted);">Choisissez les responsables locaux pour vos bureaux.</p>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <?php if(empty($requests)): ?>
        <p style="color: var(--text-muted); text-align: center;">Aucune candidature en attente.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 1rem;">Candidat</th>
                        <th style="padding: 1rem;">Siège / Wilaya</th>
                        <th style="padding: 1rem;">Description</th>
                        <th style="padding: 1rem;">Statut</th>
                        <th style="padding: 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($requests as $r): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">
                                <div><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($r['email']) ?></div>
                            </td>
                            <td style="padding: 1rem;">
                                <div><?= htmlspecialchars($r['wilaya_name']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($r['address']) ?></div>
                            </td>
                            <td style="padding: 1rem; font-size: 0.9rem;">
                                <div style="max-width: 250px;"><?= htmlspecialchars($r['description']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Contact: <?= htmlspecialchars($r['contact_info']) ?></div>
                            </td>
                            <td style="padding: 1rem;">
                                <span class="badge badge-<?= $r['status'] === 'pending' ? 'warning' : ($r['status'] === 'approved' ? 'success' : 'danger') ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: right;">
                                <?php if($r['status'] === 'pending'): ?>
                                    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege-request/review" method="POST" style="display: inline-block;">
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
