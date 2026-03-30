<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Mon Don Matériel</h1>
        <p style="color: var(--text-muted);">Suivi détaillé de votre don #<?= $donation['id'] ?></p>
    </div>
    <a href="<?= $basePath ?>/dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; color: var(--text-main); margin: 0;"><?= htmlspecialchars($donation['category']) ?></h2>
                <?php 
                    $statC = '#f59e0b'; $statT = 'En attente';
                    if($donation['status'] === 'scheduled') { $statC = '#3b82f6'; $statT = 'Planifié'; }
                    if($donation['status'] === 'collected') { $statC = '#10b981'; $statT = 'Collecté'; }
                    if($donation['status'] === 'cancelled') { $statC = '#ef4444'; $statT = 'Annulé'; }
                ?>
                <span class="badge" style="background: <?= $statC ?>22; color: <?= $statC ?>; border: 1px solid <?= $statC ?>55;">
                    <?= $statT ?>
                </span>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 0.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Description des articles offerts</h3>
                <p style="white-space: pre-wrap; line-height: 1.6; color: var(--text-main); font-size: 0.95rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px;">
                    <?= htmlspecialchars($donation['description']) ?>
                </p>
                <?php if(!empty($donation['quantity'])): ?>
                    <div style="margin-top: 1rem; font-weight: 500; color: white;">
                        Quantité / Volume fourni : <span style="color: var(--accent-color);"><?= htmlspecialchars($donation['quantity']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <h3 style="font-size: 1.2rem; margin-top: 3rem; margin-bottom: 1rem; color: white;">Réponse de l'Association & Suivi</h3>
            
            <?php if ($donation['status'] === 'scheduled' || $donation['status'] === 'collected'): ?>
                <div style="background: rgba(16,219,209,0.1); border: 1px solid rgba(16,219,209,0.3); padding: 2rem; border-radius: 8px;">
                    <h3 style="color: #10dbd1; margin-bottom: 1rem; font-size: 1.1rem;">🗓️ Collecte du Don</h3>
                    <div style="margin-bottom: 1rem; font-size: 1.1rem;">
                        <span style="color: var(--text-muted);">Date et Heure fixées :</span> 
                        <span style="font-weight: bold; color: white;">
                            <?= $donation['pickup_date'] ? date('d/m/Y à H:i', strtotime($donation['pickup_date'])) : 'Non définie' ?>
                        </span>
                    </div>
                    <?php if($donation['manager_message']): ?>
                        <div style="margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem;">
                            <span style="color: var(--text-muted); display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">Message du bureau :</span>
                            <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; font-size: 1rem; line-height: 1.5;">
                                "<?= htmlspecialchars($donation['manager_message']) ?>"
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($donation['status'] === 'cancelled'): ?>
                <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); padding: 2rem; border-radius: 8px;">
                    <h3 style="color: #ef4444; margin-bottom: 1rem; font-size: 1.1rem;">Motif de l'annulation ou du refus</h3>
                    <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; line-height: 1.6; font-size: 1rem;">
                        <?= htmlspecialchars($donation['manager_message']) ?>
                    </p>
                </div>
            <?php else: ?>
                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 2rem; border-radius: 8px; text-align: center;">
                    <p style="color: var(--text-main); margin: 0;">L'association étudie actuellement votre proposition de don. Vous serez tenu au courant très bientôt.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Context Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: var(--accent-color); margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Informations</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Proposé le</span>
                    <span><?= date('d/m/Y à H:i', strtotime($donation['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Destinataire</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Association</span>
                    <?php if($donation['association_id']): ?>
                        <span style="color: white; font-weight: 500;">
                            <?= htmlspecialchars($donation['association_name']) ?>
                        </span>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Gestion Nationale Globale</span>
                    <?php endif; ?>
                </div>
                <?php if($donation['siege_id']): ?>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Bureau Local</span>
                        <span style="color: var(--text-main);">
                            <?= htmlspecialchars($donation['siege_address']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
