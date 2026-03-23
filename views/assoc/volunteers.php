<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Bénévoles : <?= htmlspecialchars($campaign['title']) ?></h1>
        <p style="color: var(--text-muted);">Liste des citoyens inscrits pour cette mission.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/campaigns" class="btn btn-secondary">Retour aux Campagnes</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Citoyen</th>
                    <th style="padding: 1rem;">Contact</th>
                    <th style="padding: 1rem;">Date d'inscription</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($volunteers)): foreach($volunteers as $v): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($v['prenom'] . ' ' . $v['nom']) ?></div>
                        </td>
                        <td style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                            <div>📧 <?= htmlspecialchars($v['email']) ?></div>
                            <div>📞 <?= htmlspecialchars($v['phone'] ?? 'N/A') ?></div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y H:i', strtotime($v['registered_at'])) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusColor = '#f59e0b'; // registered
                                if($v['status'] === 'confirmed') $statusColor = '#10b981';
                                if($v['status'] === 'attended') $statusColor = '#3b82f6';
                                if($v['status'] === 'cancelled') $statusColor = '#ef4444';
                            ?>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                <?= ucfirst($v['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/volunteer/status" method="POST" style="display: inline;">
                                <input type="hidden" name="volunteer_id" value="<?= $v['id'] ?>">
                                <input type="hidden" name="campaign_id" value="<?= $campaign['id'] ?>">
                                <select name="status" onchange="this.form.submit()" class="glass-panel" style="padding: 0.3rem; font-size: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 4px; color: white;">
                                    <option value="registered" <?= $v['status'] === 'registered' ? 'selected' : '' ?>>Inscrit</option>
                                    <option value="confirmed" <?= $v['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                                    <option value="attended" <?= $v['status'] === 'attended' ? 'selected' : '' ?>>Présent</option>
                                    <option value="cancelled" <?= $v['status'] === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun bénévole inscrit pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
