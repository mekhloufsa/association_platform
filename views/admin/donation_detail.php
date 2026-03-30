<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails du Don Financier</h1>
        <p style="color: var(--text-muted);">Transaction #<?= $donation['id'] ?></p>
    </div>
    <a href="<?= $basePath ?>/admin/donations" class="btn btn-secondary">Retour à la liste</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 2.5rem; text-align: center; border: 2px solid var(--glass-border);">
            <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Montant du Don</div>
            <div style="font-size: 3rem; font-weight: 800; color: #10b981; line-height: 1;">
                <?= number_format($donation['amount'], 0, ',', ' ') ?> DZD
            </div>
            <div style="margin-top: 1rem;">
                <span class="badge badge-<?= $donation['status'] === 'completed' ? 'success' : 'warning' ?>" style="font-size: 0.9rem; padding: 0.4rem 1rem;">
                    <?= ucfirst($donation['status']) ?>
                </span>
            </div>
        </div>

        <div class="glass-panel" style="padding: 2rem;">
            <h3 style="font-size: 1.1rem; color: var(--accent-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">Informations du Paiement</h3>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem 0; color: var(--text-muted);">Date de transaction</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 500;"><?= date('d M Y à H:i', strtotime($donation['created_at'])) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1rem 0; color: var(--text-muted);">Type de don</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 500;"><?= ucfirst($donation['type']) ?></td>
                </tr>
            </table>

            <?php if (!empty($donation['message'])): ?>
                <div style="margin-top: 2rem;">
                    <h3 style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 0.5rem;">Message du donateur</h3>
                    <div style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border-left: 3px solid var(--accent-color); font-style: italic; color: var(--text-main); line-height: 1.6;">
                        "<?= nl2br(htmlspecialchars($donation['message'])) ?>"
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Context Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Donateur</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Nom Complet</span>
                    <a href="<?= $basePath ?>/admin/user/<?= $donation['user_id'] ?>" style="color: var(--accent-color); font-weight: 600; text-decoration: none;">
                        <?= htmlspecialchars($donation['prenom'] . ' ' . $donation['nom']) ?>
                    </a>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Email</span>
                    <span><?= htmlspecialchars($donation['email']) ?></span>
                </div>
                <?php if(!empty($donation['phone'])): ?>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Téléphone</span>
                        <span><?= htmlspecialchars($donation['phone']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Bénéficiaire</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Entité</span>
                    <?php if($donation['association_id']): ?>
                        <a href="<?= $basePath ?>/admin/association/<?= $donation['association_id'] ?>" style="color: #10b981; font-weight: 600; text-decoration: none;">
                            <?= htmlspecialchars($donation['association_name']) ?>
                        </a>
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-weight: 500;">Plateforme (Action Nationale)</span>
                    <?php endif; ?>
                </div>
                <?php if($donation['siege_id']): ?>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Siège / Antenne locale</span>
                        <a href="<?= $basePath ?>/admin/siege/<?= $donation['siege_id'] ?>" style="color: var(--text-main); text-decoration: none;">
                            <?= htmlspecialchars($donation['siege_address']) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
