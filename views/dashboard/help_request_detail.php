<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Ma Demande d'Aide</h1>
        <p style="color: var(--text-muted);">Suivi détaillé de votre demande #<?= $request['id'] ?></p>
    </div>
    <a href="<?= $basePath ?>/dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; color: var(--text-main); margin: 0;"><?= htmlspecialchars($request['subject']) ?></h2>
                <?php 
                    $statC = '#f59e0b'; if($request['status'] === 'accepted') $statC = '#10b981'; else if($request['status'] === 'rejected') $statC = '#ef4444'; 
                ?>
                <span style="padding: 0.4rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; background: <?= $statC ?>22; color: <?= $statC ?>; border: 1px solid <?= $statC ?>55;">
                    <?= ucfirst($request['status']) ?>
                </span>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 0.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Votre Description</h3>
                <p style="white-space: pre-wrap; line-height: 1.6; color: var(--text-main); font-size: 0.95rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px;">
                    <?= htmlspecialchars($request['description']) ?>
                </p>
            </div>

            <?php if (!empty($request['attachments'])): ?>
                <?php $files = json_decode($request['attachments'], true); ?>
                <?php if (is_array($files) && count($files) > 0): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1rem;">Pièces jointes fournies</h3>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <?php foreach($files as $file): ?>
                                <a href="<?= $basePath ?>/<?= htmlspecialchars($file) ?>" target="_blank" class="glass-panel" style="padding: 1rem; text-decoration: none; display: flex; align-items: center; justify-content: space-between;">
                                    <span style="color: var(--accent-color); font-weight: 500;">Ouvrir le document</span>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;"><?= htmlspecialchars(basename($file)) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <h3 style="font-size: 1.2rem; margin-top: 3rem; margin-bottom: 1rem; color: white;">Réponse de l'Association</h3>
            
            <?php if ($request['status'] === 'accepted'): ?>
                <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); padding: 2rem; border-radius: 8px;">
                    <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.1rem;">🎉 Demande Acceptée</h3>
                    <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; line-height: 1.6; font-size: 1rem;">
                        <?= htmlspecialchars($request['appointment_details']) ?>
                    </p>
                </div>
            <?php elseif ($request['status'] === 'rejected'): ?>
                <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); padding: 2rem; border-radius: 8px;">
                    <h3 style="color: #ef4444; margin-bottom: 1rem; font-size: 1.1rem;">❌ Demande Refusée</h3>
                    <p style="color: var(--text-main); margin: 0; white-space: pre-wrap; line-height: 1.6; font-size: 1rem;">
                        <?= htmlspecialchars($request['refusal_message']) ?>
                    </p>
                </div>
            <?php else: ?>
                <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); padding: 2rem; border-radius: 8px; text-align: center;">
                    <p style="color: var(--text-main); margin: 0;">Votre demande est actuellement en cours d'examen par le bureau de l'association. Vous recevrez une réponse très prochainement.</p>
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
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Envoyée le</span>
                    <span><?= date('d/m/Y à H:i', strtotime($request['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="color: #10b981; margin-bottom: 1rem; font-size: 1.1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Destinataire</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Association</span>
                    <?php if($request['association_id']): ?>
                        <span style="color: white; font-weight: 500;">
                            <?= htmlspecialchars($request['association_name']) ?>
                        </span>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Gestion Nationale Globale</span>
                    <?php endif; ?>
                </div>
                <?php if($request['siege_id']): ?>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 0.8rem;">Bureau Local</span>
                        <span style="color: var(--text-main);">
                            <?= htmlspecialchars($request['siege_address']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
