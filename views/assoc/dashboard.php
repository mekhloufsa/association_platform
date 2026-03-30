<?php $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text"><?= htmlspecialchars($association['name']) ?></h1>
        <p style="color: var(--text-muted);">Espace Président d'Association - Administration Nationale</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="<?= $base ?>/assoc/add-campaign" class="btn btn-primary">+ Nouvelle Campagne</a>
        <a href="<?= $base ?>/assoc/add-siege" class="btn btn-secondary">+ Ajouter un Siège</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Antennes Locales</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: var(--accent-color);"><?= $siegesCount ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Campagnes Actives</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #f59e0b;"><?= $campaignsCount ?></span>
    </div>
    <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
        <span style="display: block; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Bénévoles Totaux</span>
        <span style="font-size: 1.8rem; font-weight: 700; color: #10b981;">0</span>
    </div>
</div>

<div class="feature-grid" style="margin-top: 3rem;">
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/sieges" class="feature-card glass-panel" style="text-decoration: none; color: inherit;">
        <div style="font-size: 2rem; margin-bottom: 1rem;">🏢</div>
        <h3>Gestion des Sièges</h3>
        <p>Gérez vos antennes locales à travers les 58 wilayas d'Algérie.</p>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/campaigns" class="feature-card glass-panel" style="text-decoration: none; color: inherit;">
        <div style="font-size: 2rem; margin-bottom: 1rem;">📢</div>
        <h3>Campagnes & Appels</h3>
        <p>Créez et suivez vos campagnes de dons et de bénévolat.</p>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/help-requests" class="feature-card glass-panel" style="text-decoration: none; color: inherit;">
        <div style="font-size: 2rem; margin-bottom: 1rem;">🆘</div>
        <h3>Demandes d'Aide</h3>
        <p>Gérez et répondez aux demandes d'aide directe des citoyens.</p>
    </a>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/settings" class="feature-card glass-panel" style="text-decoration: none; color: inherit;">
        <div style="font-size: 2rem; margin-bottom: 1rem;">⚙️</div>
        <h3>Paramètres</h3>
        <p>Définissez votre message de remerciement et autres réglages.</p>
    </a>
</div>

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
