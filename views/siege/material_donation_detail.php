<?php
$base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détail du Don Matériel</h1>
        <p style="color: var(--text-muted);">Référence #<?= $donation['id'] ?> — Reçu le <?= date('d/m/Y H:i', strtotime($donation['created_at'])) ?></p>
    </div>
    <a href="javascript:history.back()" class="btn btn-secondary">← Retour</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Info -->
    <div class="glass-panel" style="padding: 2.5rem;">
        <h3 style="color: var(--accent-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-box-open"></i> Informations sur le Don
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
            <div>
                <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.3rem;">Catégorie</label>
                <div style="font-size: 1.1rem; font-weight: 600; color: white;">
                    <span class="badge badge-secondary"><?= htmlspecialchars($donation['category']) ?></span>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.3rem;">Quantité / Estimation</label>
                <div style="font-size: 1.1rem; font-weight: 600; color: white;"><?= htmlspecialchars($donation['quantity'] ?: 'Non spécifiée') ?></div>
            </div>
        </div>

        <div style="margin-bottom: 2.5rem;">
            <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Description des articles</label>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.5rem; line-height: 1.7; color: var(--text-main); font-size: 1.05rem;">
                <?= nl2br(htmlspecialchars($donation['description'])) ?>
            </div>
        </div>

        <?php if($donation['status'] === 'scheduled'): ?>
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; border-radius: 12px; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 2rem; color: #10b981;"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div style="font-weight: 700; color: #10b981;">Collecte planifiée</div>
                    <div style="color: white; font-size: 1.1rem;">Le <?= date('d/m/Y à H:i', strtotime($donation['pickup_date'])) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($donation['manager_message']): ?>
            <div style="margin-top: 2rem;">
                <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Message du Responsable</label>
                <div style="border-left: 4px solid var(--accent-color); padding-left: 1rem; font-style: italic; color: var(--text-muted);">
                    "<?= htmlspecialchars($donation['manager_message']) ?>"
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: Donor & Actions -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Donor Info -->
        <div class="glass-panel" style="padding: 2rem;">
            <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Donateur</h3>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 45px; height: 45px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white;">
                    <?= strtoupper(substr($donation['prenom'], 0, 1)) ?>
                </div>
                <div>
                    <div style="font-weight: 700; color: white;"><?= htmlspecialchars($donation['prenom'] . ' ' . $donation['nom']) ?></div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Citoyen</div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                    <i class="fas fa-envelope" style="width: 20px;"></i> <?= htmlspecialchars($donation['email']) ?>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted);">
                    <i class="fas fa-phone" style="width: 20px;"></i> <?= htmlspecialchars($donation['phone'] ?: 'N/A') ?>
                </div>
            </div>
        </div>

        <!-- Status & Management -->
        <div class="glass-panel" style="padding: 2rem;">
            <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1.5rem; text-transform: uppercase;">Statut Actuel</h3>
            <?php 
                $statusColor = '#f59e0b';
                if($donation['status'] === 'collected') $statusColor = '#10b981';
                if($donation['status'] === 'cancelled') $statusColor = '#ef4444';
                if($donation['status'] === 'scheduled') $statusColor = '#3b82f6';
            ?>
            <div style="font-size: 1.5rem; font-weight: 800; color: <?= $statusColor ?>; margin-bottom: 2rem; text-align: center;">
                <?= strtoupper($donation['status']) ?>
            </div>

            <?php if (($_SESSION['user_role'] === 'president_siege') && ($donation['status'] === 'pending' || $donation['status'] === 'scheduled')): ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php if($donation['status'] === 'scheduled'): ?>
                        <form action="<?= $base ?>/siege/donation/status" method="POST" style="margin: 0;">
                            <input type="hidden" name="id" value="<?= $donation['id'] ?>">
                            <input type="hidden" name="status" value="collected">
                            <button type="submit" class="btn btn-primary" style="width: 100%; background: #10b981; border: none; padding: 1rem;">Marquer comme Collecté</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php elseif($_SESSION['user_role'] === 'president_assoc'): ?>
                <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                    Lecture seule (Géré par le siège)
                </div>
            <?php endif; ?>
        </div>

        <!-- Assoc Info (if Assoc view) -->
        <?php if(isset($donation['siege_address'])): ?>
            <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.02);">
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">Siège Destinataire</div>
                <div style="font-size: 0.9rem; color: white; font-weight: 500;"><?= htmlspecialchars($donation['siege_address']) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
