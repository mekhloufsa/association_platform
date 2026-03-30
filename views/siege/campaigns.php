<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Mes Campagnes Locales</h1>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/add-campaign" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
        <span style="font-size: 1.2rem;">+</span> Proposer une Campagne
    </a>
</div>

<?php if(isset($_SESSION['flash_success'])): ?>
    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
        <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<div class="glass-panel" style="padding: 2rem;">
    <?php if(empty($campaigns)): ?>
        <p style="color: var(--text-muted); text-align: center; margin: 2rem 0;">Aucune campagne locale trouvée.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <th style="text-align: left; padding: 1rem; color: var(--text-muted);">Titre</th>
                        <th style="text-align: left; padding: 1rem; color: var(--text-muted);">Type Besoin</th>
                        <th style="text-align: left; padding: 1rem; color: var(--text-muted);">Période</th>
                        <th style="text-align: center; padding: 1rem; color: var(--text-muted);">Statut (Workflow)</th>
                        <th style="text-align: center; padding: 1rem; color: var(--text-muted);">État public</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($campaigns as $camp): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 500; color: white;"><?= htmlspecialchars($camp['title']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($camp['location']) ?></div>
                            </td>
                            <td style="padding: 1rem; color: var(--text-main);">
                                <?= $camp['need_type'] === 'personnel' ? 'Bénévoles' : 'Collecte financière' ?>
                            </td>
                            <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-muted);">
                                <?= date('d/m/Y', strtotime($camp['start_date'])) ?> au <?= date('d/m/Y', strtotime($camp['end_date'])) ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?php 
                                    $appC = '#f59e0b'; $appT = 'En attente Bureau';
                                    if($camp['approval_status'] === 'approved') { $appC = '#10b981'; $appT = 'Approuvée'; }
                                    if($camp['approval_status'] === 'rejected') { $appC = '#ef4444'; $appT = 'Refusée'; }
                                ?>
                                <span style="background: <?= $appC ?>22; color: <?= $appC ?>; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; border: 1px solid <?= $appC ?>55;">
                                    <?= $appT ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?php if($camp['status'] === 'open'): ?>
                                    <span style="color: #10b981; font-weight: 600; font-size: 0.85rem;">En cours</span>
                                <?php elseif($camp['status'] === 'closed'): ?>
                                    <span style="color: var(--text-muted); font-size: 0.85rem;">Fermée</span>
                                <?php else: ?>
                                    <span style="color: #6c757d; font-size: 0.85rem;">Terminée</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
