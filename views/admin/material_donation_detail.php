<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails du Don Matériel</h1>
        <p style="color: var(--text-muted);">Consultation du don en nature #<?= $donation['id'] ?></p>
    </div>
    <a href="<?= $basePath ?>/admin/material-donations" class="btn btn-secondary">Retour à la liste</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; color: var(--text-main); margin: 0;"><?= htmlspecialchars($donation['category']) ?></h2>
                <?php 
                    $statusBadge = 'warning'; $statusText = 'En attente';
                    if($donation['status'] === 'scheduled') { $statusBadge = 'info'; $statusText = 'Planifié'; }
                    if($donation['status'] === 'collected') { $statusBadge = 'success'; $statusText = 'Collecté'; }
                    if($donation['status'] === 'cancelled') { $statusBadge = 'danger'; $statusText = 'Annulé'; }
                ?>
                <span class="badge badge-<?= $statusBadge ?>" style="font-size: 0.85rem; padding: 0.4rem 1rem;">
                    <?= $statusText ?>
                </span>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 0.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Description des articles</h3>
                <p style="white-space: pre-wrap; line-height: 1.6; color: var(--text-main); font-size: 0.95rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px;">
                    <?= htmlspecialchars($donation['description']) ?>
                </p>
                <?php if(!empty($donation['quantity'])): ?>
                    <div style="margin-top: 1rem; font-weight: 600; color: var(--accent-color);">
                        Quantité/Volume détaillé : <?= htmlspecialchars($donation['quantity']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($donation['status'] === 'scheduled' || $donation['status'] === 'collected'): ?>
                <div style="margin-top: 1rem; background: rgba(16,219,209,0.1); border: 1px solid rgba(16,219,209,0.3); padding: 1.5rem; border-radius: 8px;">
                    <h3 style="color: #10dbd1; margin-bottom: 0.5rem; font-size: 1rem;">🗓️ Collecte & Rendez-vous</h3>
                    <div style="margin-bottom: 0.5rem;">
                        <span style="color: var(--text-muted);">Date de collecte / livraison :</span> 
                        <span style="font-weight: 600; color: white;">
                            <?= $donation['pickup_date'] ? date('d/m/Y H:i', strtotime($donation['pickup_date'])) : 'Non définie' ?>
                        </span>
                    </div>
                    <?php if($donation['manager_message']): ?>
                        <div style="margin-top: 1rem;">
                            <span style="color: var(--text-muted); display: block; margin-bottom: 0.2rem;">Message du bureau :</span>
                            <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; font-size: 0.9rem; font-style: italic;">"<?= htmlspecialchars($donation['manager_message']) ?>"</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($donation['status'] === 'cancelled' && $donation['manager_message']): ?>
                <div style="margin-top: 1rem; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); padding: 1.5rem; border-radius: 8px;">
                    <h3 style="color: #ef4444; margin-bottom: 0.5rem; font-size: 1rem;">Motif d'annulation</h3>
                    <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; line-height: 1.5; font-size: 0.9rem;"><?= htmlspecialchars($donation['manager_message']) ?></p>
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
                <div style="margin-top: 0.5rem;">
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Date d'offre</span>
                    <span><?= date('d/m/Y à H:i', strtotime($donation['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Bureau / Association destinataire</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Association</span>
                    <?php if($donation['association_id']): ?>
                        <a href="<?= $basePath ?>/admin/association/<?= $donation['association_id'] ?>" style="color: #10b981; font-weight: 600; text-decoration: none;">
                            <?= htmlspecialchars($donation['association_name']) ?>
                        </a>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Plateforme Web (Non spécifique)</span>
                    <?php endif; ?>
                </div>
                <?php if($donation['siege_id']): ?>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Siège Local</span>
                        <a href="<?= $basePath ?>/admin/siege/<?= $donation['siege_id'] ?>" style="color: var(--text-main); text-decoration: none;">
                            <?= htmlspecialchars($donation['siege_address']) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
