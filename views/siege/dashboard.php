<div style="padding: 2rem;">
<?php $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="gradient-text">Espace Président de Siège (Wilaya)</h1>
            <p>Bienvenue <?= htmlspecialchars($name) ?> ! Gérez votre antenne locale.</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="<?= $base ?>/siege/add-campaign" class="btn btn-primary">+ Nouvelle Campagne locale</a>
        </div>
    </div>
    
    <div class="feature-grid" style="margin-top: 2rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/help-requests" class="feature-card glass-panel" style="text-decoration: none; display: block;">
            <h3>Demandes d'Aide</h3>
            <p>Vérifier les dossiers et accepter/refuser les demandes locales.</p>
        </a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/donations" class="feature-card glass-panel" style="text-decoration: none; display: block;">
            <h3>Gestion des Dons Matériels</h3>
            <p>Marquer à récupérer et fixer des rendez-vous locaux.</p>
        </a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/volunteers" class="feature-card glass-panel" style="text-decoration: none; display: block;">
            <h3>Bénévoles Locaux</h3>
            <p>Gérer les inscriptions aux campagnes dans votre wilaya.</p>
        </a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/campaigns" class="feature-card glass-panel" style="text-decoration: none; display: block; border: 1px dashed var(--accent-color);">
            <h3 style="color: var(--accent-color);">Campagnes Locales</h3>
            <p>Proposer et suivre vos propres campagnes et collectes.</p>
        </a>
    </div>

    <?php if($siege): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-top: 3rem;">
        <!-- Financial Donations -->
        <div class="glass-panel" style="padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Dons Financiers Reçus</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                            <th style="padding: 0.75rem 0;">Donateur</th>
                            <th style="padding: 0.75rem 0;">Montant</th>
                            <th style="padding: 0.75rem 0; text-align: right;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($donations)): foreach($donations as $don): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="padding: 0.75rem 0;">
                                    <div style="font-weight: 500;"><?= htmlspecialchars($don['prenom'] . ' ' . $don['nom']) ?></div>
                                </td>
                                <td style="padding: 0.75rem 0; color: var(--accent-color); font-weight: 600;">
                                    <?= number_format($don['amount'], 0, ',', ' ') ?> DZD
                                </td>
                                <td style="padding: 0.75rem 0; text-align: right; color: var(--text-muted); font-size: 0.85rem;">
                                    <?= date('d/m/Y', strtotime($don['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="3" style="padding: 1rem; text-align: center; color: var(--text-muted);">Aucun don financier reçu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Material Donations -->
        <div class="glass-panel" style="padding: 2rem;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Dons Matériels Reçus</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                            <th style="padding: 0.75rem 0;">Donateur</th>
                            <th style="padding: 0.75rem 0;">Objet</th>
                            <th style="padding: 0.75rem 0; text-align: right;">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($materials)): foreach($materials as $m): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border);">
                                <td style="padding: 0.75rem 0;">
                                    <div style="font-weight: 500;"><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></div>
                                </td>
                                <td style="padding: 0.75rem 0; font-size: 0.9rem;">
                                    <strong><?= htmlspecialchars($m['category']) ?></strong><br>
                                    <span style="color: var(--text-muted);"><?= htmlspecialchars($m['quantity']) ?></span>
                                </td>
                                <td style="padding: 0.75rem 0; text-align: right;">
                                    <span style="font-size: 0.75rem; color: #f59e0b;"><?= ucfirst($m['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="3" style="padding: 1rem; text-align: center; color: var(--text-muted);">Aucun don matériel reçu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="glass-panel" style="padding: 2rem; margin-top: 2rem; text-align: center; border: 1px dashed var(--accent-color);">
            <h3 style="color: var(--accent-color);">Siège non assigné</h3>
            <p style="color: var(--text-muted);">Vous n'êtes actuellement assigné à aucun siège. Veuillez contacter votre président d'association.</p>
        </div>
    <?php endif; ?>
</div>
