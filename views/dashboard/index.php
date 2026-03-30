<?php $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="gradient-text">Mon Espace Citoyen</h1>
        <div style="display: flex; gap: 1rem;">
            <?php if($_SESSION['user_role'] === 'user'): ?>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/request/assoc-create" class="btn btn-secondary" style="border: 1px dashed var(--accent-color);">Créer une Association</a>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/request/siege-apply" class="btn btn-secondary">Postuler pour un siège</a>
            <?php endif; ?>
            <?php if($_SESSION['user_role'] !== 'admin'): ?>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/help-request" class="btn btn-secondary">Demander d'aide</a>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/donation" class="btn btn-primary">Faire un don</a>
            <?php endif; ?>
        </div>
    </div>
    
    <p>Bienvenue <?= htmlspecialchars($name) ?> ! Voici vos activités récentes.</p>

    <?php if($_SESSION['user_role'] !== 'admin'): ?>
    <!-- Mes Demandes d'Aide (Historique) -->
    <div class="glass-panel" style="margin-top: 2.5rem; padding: 2rem;">
        <h2 class="gradient-text" style="margin-bottom: 1.5rem;">Mes Demandes d'Aide</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                        <th style="padding: 1rem;">Sujet / Description</th>
                        <th style="padding: 1rem;">Antenne / Association</th>
                        <th style="padding: 1rem;">Statut</th>
                        <th style="padding: 1rem;">Résultat / Réponse</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($requests)): foreach($requests as $r): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 600;"><?= htmlspecialchars($r['subject']) ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);"><?= mb_strimwidth(htmlspecialchars($r['description']), 0, 80, "...") ?></div>
                            </td>
                            <td style="padding: 1rem; font-size: 0.85rem;">
                                <div>Bureau de <?= htmlspecialchars($r['wilaya_name'] ?? 'N/A') ?></div>
                                <div style="color: var(--text-muted);"><?= htmlspecialchars($r['association_name'] ?? 'N/A') ?></div>
                            </td>
                            <td style="padding: 1rem;">
                                <?php 
                                    $statusColor = '#f59e0b';
                                    if($r['status'] === 'accepted') $statusColor = '#10b981';
                                    if($r['status'] === 'rejected') $statusColor = '#ef4444';
                                ?>
                                <span style="padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; font-size: 0.85rem;">
                                <?php if($r['status'] === 'accepted'): ?>
                                    <div style="color: #10b981; font-weight: 600;">Demande Acceptée</div>
                                <?php elseif($r['status'] === 'rejected'): ?>
                                    <div style="color: #ef4444; font-weight: 600;">Demande Refusée</div>
                                <?php else: ?>
                                    <div style="color: var(--text-muted);">En attente</div>
                                <?php endif; ?>
                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/help-request/<?= $r['id'] ?>" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border: 1px dashed var(--accent-color); margin-top: 0.5rem;">Visualiser la réponse →</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune demande envoyée.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2.5rem;">
        <!-- Historique des Dons -->
        <div class="glass-panel" style="padding: 2rem;">
            <h2 class="gradient-text" style="margin-bottom: 1.5rem;">Historique des Dons</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                    <tbody>
                        <?php if(!empty($donations)): foreach($donations as $d): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); font-size: 0.85rem;">
                                <td style="padding: 0.8rem;">
                                    <div style="font-weight: 500;"><?= date('d/m/Y', strtotime($d['created_at'])) ?></div>
                                    <div style="color: var(--text-muted);"><?= htmlspecialchars($d['association_name'] ?? 'Association') ?></div>
                                </td>
                                <td style="padding: 0.8rem; text-align: right; font-weight: 600; color: var(--accent-color);">
                                    <?= number_format($d['amount'], 0, ',', ' ') ?> DZD
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" style="padding: 1.5rem; text-align: center; color: var(--text-muted);">Aucun don effectué.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <h3 class="gradient-text" style="margin-top: 2rem; margin-bottom: 1rem; font-size: 1.2rem;">Dons Matériels</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                    <tbody>
                        <?php if(!empty($materialDonations)): foreach($materialDonations as $md): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); font-size: 0.85rem;">
                                <td style="padding: 0.8rem;">
                                    <div style="font-weight: 500; font-size: 0.9rem;"><?= htmlspecialchars($md['category']) ?> - <?= htmlspecialchars($md['quantity']) ?></div>
                                    <div style="color: var(--text-muted);"><?= htmlspecialchars($md['association_name'] ?? 'Association') ?></div>
                                    <div style="color: var(--text-muted); font-size: 0.75rem;">Le <?= date('d/m/Y', strtotime($md['created_at'])) ?></div>
                                </td>
                                <td style="padding: 0.8rem; text-align: right;">
                                    <?php 
                                        $mdColor = '#f59e0b'; // pending
                                        $mdStatus = 'En attente';
                                        if($md['status'] === 'scheduled') { $mdColor = '#3b82f6'; $mdStatus = 'Planifié'; }
                                        if($md['status'] === 'collected') { $mdColor = '#10b981'; $mdStatus = 'Collecté'; }
                                        if($md['status'] === 'cancelled') { $mdColor = '#ef4444'; $mdStatus = 'Refusé / Annulé'; }
                                    ?>
                                    <span style="padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; background: <?= $mdColor ?>22; color: <?= $mdColor ?>; border: 1px solid <?= $mdColor ?>55; display: inline-block; margin-bottom: 0.5rem;">
                                        <?= $mdStatus ?>
                                    </span>
                                    <br>
                                    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/material-donation/<?= $md['id'] ?>" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border: 1px dashed var(--accent-color);">Voir les détails →</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" style="padding: 1.5rem; text-align: center; color: var(--text-muted);">Aucun don matériel effectué.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/donation" style="display: block; text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--accent-color);">Faire un nouveau don →</a>
        </div>

        <!-- Mes Missions -->
        <div class="glass-panel" style="padding: 2rem;">
            <h2 class="gradient-text" style="margin-bottom: 1.5rem;">Mes Missions</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
                    <tbody>
                        <?php if(!empty($volunteering)): foreach($volunteering as $v): ?>
                            <tr style="border-bottom: 1px solid var(--glass-border); font-size: 0.85rem;">
                                <td style="padding: 0.8rem;">
                                    <div style="font-weight: 600;"><?= htmlspecialchars($v['campaign_title']) ?></div>
                                    <div style="color: var(--text-muted);"><?= htmlspecialchars($v['association_name']) ?></div>
                                </td>
                                <td style="padding: 0.8rem; text-align: right;">
                                    <span style="padding: 0.15rem 0.5rem; border-radius: 10px; font-size: 0.7rem; background: rgba(255,255,255,0.1);">
                                        <?= ucfirst($v['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="2" style="padding: 1.5rem; text-align: center; color: var(--text-muted);">Aucune mission enregistrée.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard/campaigns" style="display: block; text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--accent-color);">Découvrir des missions →</a>
        </div>
    </div>

    <!-- Mes Candidatures (Nouveauté Phase 2) -->
    <div class="glass-panel" style="margin-top: 2.5rem; padding: 2rem; border: 1px solid var(--accent-color);">
        <h2 class="gradient-text" style="margin-bottom: 1.5rem;">Suivi de mes Candidatures</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Demandes de Création d'Association -->
            <div>
                <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1rem;">Création d'Association</h3>
                <?php if(!empty($assocRequests)): foreach($assocRequests as $ar): ?>
                    <div class="glass-panel" style="padding: 1rem; margin-bottom: 1rem; background: rgba(255,255,255,0.02);">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <span style="font-weight: 600; color: white;"><?= htmlspecialchars($ar['name']) ?></span>
                            <?php 
                                $arColor = $ar['status'] === 'approved' ? '#10b981' : ($ar['status'] === 'rejected' ? '#ef4444' : '#f59e0b');
                            ?>
                            <span style="font-size: 0.7rem; color: <?= $arColor ?>; border: 1px solid <?= $arColor ?>; padding: 0.1rem 0.4rem; border-radius: 4px;"><?= ucfirst($ar['status']) ?></span>
                        </div>
                        <?php if($ar['status'] === 'rejected' && $ar['admin_message']): ?>
                            <p style="font-size: 0.8rem; color: #ef4444; border-top: 1px solid rgba(239, 68, 68, 0.2); padding-top: 0.5rem; margin-top: 0.5rem;">
                                <strong>Admin :</strong> <?= htmlspecialchars($ar['admin_message']) ?>
                            </p>
                        <?php endif; ?>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Le <?= date('d/m/Y', strtotime($ar['created_at'])) ?></div>
                    </div>
                <?php endforeach; else: ?>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">Aucune demande de création.</p>
                <?php endif; ?>
            </div>

            <!-- Demandes de Responsable de Siège -->
            <div>
                <h3 style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1rem;">Responsable de Siège</h3>
                <?php if(!empty($siegeRequests)): foreach($siegeRequests as $sr): ?>
                    <div class="glass-panel" style="padding: 1rem; margin-bottom: 1rem; background: rgba(255,255,255,0.02);">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div>
                                <div style="font-weight: 600; color: white;"><?= htmlspecialchars($sr['association_name']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($sr['address']) ?></div>
                            </div>
                            <?php 
                                $srColor = $sr['status'] === 'approved' ? '#10b981' : ($sr['status'] === 'rejected' ? '#ef4444' : '#f59e0b');
                            ?>
                            <span style="font-size: 0.7rem; color: <?= $srColor ?>; border: 1px solid <?= $srColor ?>; padding: 0.1rem 0.4rem; border-radius: 4px;"><?= ucfirst($sr['status']) ?></span>
                        </div>
                        <?php if($sr['status'] === 'rejected' && $sr['president_message']): ?>
                            <p style="font-size: 0.8rem; color: #ef4444; border-top: 1px solid rgba(239, 68, 68, 0.2); padding-top: 0.5rem; margin-top: 0.5rem;">
                                <strong>Président :</strong> <?= htmlspecialchars($sr['president_message']) ?>
                            </p>
                        <?php endif; ?>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Le <?= date('d/m/Y', strtotime($sr['created_at'])) ?></div>
                    </div>
                <?php endforeach; else: ?>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">Aucune candidature pour un siège.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="glass-panel" style="margin-top: 2.5rem; padding: 2rem; text-align: center;">
            <h2 class="gradient-text" style="margin-bottom: 1rem;">Espace Citoyen</h2>
            <p style="color: var(--text-muted);">En tant qu'administrateur, vous n'avez pas de profil citoyen actif avec des demandes d'aide ou des dons.</p>
            <p style="color: var(--text-muted); margin-top: 0.5rem;">Veuillez utiliser l'Espace Admin pour la gestion globale de la plateforme.</p>
            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-primary" style="margin-top: 1.5rem;">Aller au Dashboard Admin</a>
        </div>
    <?php endif; ?>
