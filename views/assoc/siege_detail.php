<?php
// Helper: base path
$base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails du Siège — <?= htmlspecialchars($siege['wilaya_name']) ?></h1>
        <p style="color: var(--text-muted);">Association : <?= htmlspecialchars($association['name']) ?></p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="<?= $base ?>/assoc/sieges" class="btn btn-secondary">← Retour à la liste</a>
        <a href="<?= $base ?>/assoc/siege/edit/<?= $siege['id'] ?>" class="btn btn-secondary">Modifier</a>
    </div>
</div>

<!-- Info Card -->
<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div>
        <h3 style="color: var(--accent-color); margin-bottom: 1rem;">Informations du Siège</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.95rem;">
            <div>
                <label style="color: var(--text-muted); font-size: 0.8rem; display: block;">Wilaya</label>
                <span><?= htmlspecialchars($siege['wilaya_name']) ?> (<?= $siege['wilaya_id'] ?>)</span>
            </div>
            <div>
                <label style="color: var(--text-muted); font-size: 0.8rem; display: block;">Adresse</label>
                <span><?= htmlspecialchars($siege['address']) ?></span>
            </div>
            <div>
                <label style="color: var(--text-muted); font-size: 0.8rem; display: block;">Date d'ouverture</label>
                <span><?= date('d/m/Y', strtotime($siege['created_at'])) ?></span>
            </div>
        </div>
    </div>
    <div>
        <h3 style="color: var(--accent-color); margin-bottom: 1rem;">Responsable</h3>
        <?php if ($siege['manager_user_id']): ?>
            <div style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1rem;">
                <div style="width: 50px; height: 50px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: white; font-weight: 700; flex-shrink: 0;">
                    <?= strtoupper(substr($siege['manager_prenom'] ?? '?', 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 1rem;"><?= htmlspecialchars($siege['manager_prenom'] . ' ' . $siege['manager_nom']) ?></div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($siege['manager_email']) ?></div>
                </div>
            </div>
        <?php else: ?>
            <div style="padding: 1.5rem; border: 1px dashed var(--glass-border); border-radius: 12px; text-align: center;">
                <p style="color: #ef4444; font-weight: 600;">Aucun responsable assigné</p>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Le siège n'est pas visible dans l'annuaire public.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Statistics Summary -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color);">
            <?= count($donations) ?>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Dons Financiers</div>
        <?php
        $totalAmount = array_sum(array_column($donations, 'amount'));
        ?>
        <div style="font-size: 0.8rem; color: var(--primary-color); margin-top: 0.5rem;">
            Total : <?= number_format($totalAmount, 0, ',', ' ') ?> DZD
        </div>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color);">
            <?= count($materialDonations) ?>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Dons Matériels</div>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color);">
            <?= count($helpRequests) ?>
        </div>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Demandes d'Aide</div>
        <?php
        $pendingCount = count(array_filter($helpRequests, fn($r) => $r['status'] === 'pending'));
        ?>
        <?php if ($pendingCount > 0): ?>
        <div style="font-size: 0.8rem; color: #f59e0b; margin-top: 0.5rem;"><?= $pendingCount ?> en attente</div>
        <?php endif; ?>
    </div>
</div>

<!-- Tabs: Donations Financières -->
<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--accent-color); margin-bottom: 1.5rem;">💰 Historique des Dons Financiers (<?= count($donations) ?>)</h3>
    <?php if (!empty($donations)): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left; font-size: 0.85rem;">
                        <th style="padding: 0.75rem;">Donateur</th>
                        <th style="padding: 0.75rem;">Montant</th>
                        <th style="padding: 0.75rem;">Statut</th>
                        <th style="padding: 0.75rem;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donations as $d): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 0.75rem; font-size: 0.9rem;">
                                <div style="font-weight: 500;"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($d['email']) ?></div>
                            </td>
                            <td style="padding: 0.75rem; font-weight: 700; color: var(--accent-color);">
                                <?= number_format($d['amount'], 0, ',', ' ') ?> DZD
                            </td>
                            <td style="padding: 0.75rem;">
                                <?php $sc = $d['status'] === 'completed' ? '#10b981' : '#f59e0b'; ?>
                                <span style="padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; background: <?= $sc ?>22; color: <?= $sc ?>; border: 1px solid <?= $sc ?>55;">
                                    <?= ucfirst($d['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; color: var(--text-muted);">
                                <?= date('d/m/Y H:i', strtotime($d['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Aucun don financier pour ce siège.</p>
    <?php endif; ?>
</div>

<!-- Dons Matériels -->
<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--accent-color); margin-bottom: 1.5rem;">📦 Dons Matériels (<?= count($materialDonations) ?>)</h3>
    <?php if (!empty($materialDonations)): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left; font-size: 0.85rem;">
                        <th style="padding: 0.75rem;">Donateur</th>
                        <th style="padding: 0.75rem;">Catégorie</th>
                        <th style="padding: 0.75rem;">Description</th>
                        <th style="padding: 0.75rem;">Qté</th>
                        <th style="padding: 0.75rem;">Statut</th>
                        <th style="padding: 0.75rem;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materialDonations as $md): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 0.75rem; font-size: 0.9rem;">
                                <div style="font-weight: 500;"><?= htmlspecialchars($md['prenom'] . ' ' . $md['nom']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($md['email']) ?></div>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; font-weight: 500; color: var(--accent-color);">
                                <?= htmlspecialchars(ucfirst($md['category'])) ?>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; color: var(--text-muted);">
                                <?= htmlspecialchars(mb_strimwidth($md['description'], 0, 60, '...')) ?>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; font-weight: 600;">
                                <?= htmlspecialchars($md['quantity']) ?>
                            </td>
                            <td style="padding: 0.75rem;">
                                <?php $mc = $md['status'] === 'received' ? '#10b981' : ($md['status'] === 'rejected' ? '#ef4444' : '#f59e0b'); ?>
                                <span style="padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; background: <?= $mc ?>22; color: <?= $mc ?>; border: 1px solid <?= $mc ?>55;">
                                    <?= ucfirst($md['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; color: var(--text-muted);">
                                <?= date('d/m/Y', strtotime($md['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Aucun don matériel pour ce siège.</p>
    <?php endif; ?>
</div>

<!-- Demandes d'Aide -->
<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--accent-color); margin-bottom: 1.5rem;">🆘 Historique des Demandes d'Aide (<?= count($helpRequests) ?>)</h3>
    <?php if (!empty($helpRequests)): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left; font-size: 0.85rem;">
                        <th style="padding: 0.75rem;">Citoyen</th>
                        <th style="padding: 0.75rem;">Sujet</th>
                        <th style="padding: 0.75rem;">Statut</th>
                        <th style="padding: 0.75rem;">Réponse</th>
                        <th style="padding: 0.75rem;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($helpRequests as $hr): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 0.75rem; font-size: 0.9rem;">
                                <div style="font-weight: 500;"><?= htmlspecialchars($hr['prenom'] . ' ' . $hr['nom']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($hr['email']) ?></div>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.9rem;">
                                <div style="font-weight: 500;"><?= htmlspecialchars($hr['subject']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars(mb_strimwidth($hr['description'], 0, 60, '...')) ?></div>
                            </td>
                            <td style="padding: 0.75rem;">
                                <?php $hc = $hr['status'] === 'accepted' ? '#10b981' : ($hr['status'] === 'rejected' ? '#ef4444' : '#f59e0b'); ?>
                                <span style="padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; background: <?= $hc ?>22; color: <?= $hc ?>; border: 1px solid <?= $hc ?>55;">
                                    <?= ucfirst($hr['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.8rem; color: var(--text-muted); max-width: 200px;">
                                <?php if ($hr['status'] === 'accepted' && $hr['appointment_details']): ?>
                                    <span style="color: #10b981;"><?= htmlspecialchars($hr['appointment_details']) ?></span>
                                <?php elseif ($hr['status'] === 'rejected' && $hr['refusal_message']): ?>
                                    <span style="color: #ef4444;"><?= htmlspecialchars(mb_strimwidth($hr['refusal_message'], 0, 80, '...')) ?></span>
                                <?php else: ?>
                                    <span>—</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem; font-size: 0.85rem; color: var(--text-muted); white-space: nowrap;">
                                <?= date('d/m/Y', strtotime($hr['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Aucune demande d'aide pour ce siège.</p>
    <?php endif; ?>
</div>
