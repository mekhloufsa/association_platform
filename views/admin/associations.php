<?php str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Gestion des Associations</h1>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<!-- Filters & Search -->
<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/associations" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
        <div style="flex: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Rechercher</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, description..." 
                   style="width: 100%; padding: 0.7rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
        </div>
        <div>
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Statut</label>
            <select name="status" style="padding: 0.7rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                <option value="">Tous les statuts</option>
                <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approuvée</option>
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>En attente</option>
                <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejetée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.5rem;">Filtrer</button>
        <?php if($search || $status): ?>
            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/associations" class="btn btn-secondary" style="padding: 0.7rem 1rem;">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Nom</th>
                    <th style="padding: 1rem;">Président</th>
                    <th style="padding: 1rem;">Wilaya</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($associations)): foreach($associations as $assoc): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($assoc['name']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= mb_strimwidth(htmlspecialchars($assoc['description']), 0, 50, "...") ?></div>
                        </td>
                        <td style="padding: 1rem; color: var(--text-muted);">
                            <?= htmlspecialchars($assoc['president_prenom'] . ' ' . $assoc['president_nom']) ?>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= htmlspecialchars($assoc['siege_social_wilaya'] ?? 'N/A') ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusColor = '#f59e0b';
                                if($assoc['national_account_status'] === 'approved') $statusColor = '#10b981';
                                if($assoc['national_account_status'] === 'rejected') $statusColor = '#ef4444';
                            ?>
                            <span style="padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                <?= ucfirst($assoc['national_account_status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/<?= $assoc['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">Voir Détails</a>
                                <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?');" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $assoc['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="background: #ef4444; border: none; padding: 0.4rem 0.6rem; font-size: 0.75rem;">Supprimer</button>
                                </form>
                                <?php if($assoc['national_account_status'] === 'pending'): ?>
                                    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/validate" method="POST" style="display: inline;">
                                        <input type="hidden" name="assoc_id" value="<?= $assoc['id'] ?>">
                                        <button type="submit" name="status" value="approved" class="btn btn-primary" style="padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #10b981;">Approuver</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune association trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
