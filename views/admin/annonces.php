<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Gestion des Annonces</h1>
        <p style="color: var(--text-muted);">Publiez de nouvelles actualités ou appels aux citoyens.</p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/add-annonce" class="btn btn-primary">+ Nouvelle Annonce</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Titre</th>
                    <th style="padding: 1rem;">Visibilité</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem;">Fichiers</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($annonces)): foreach($annonces as $annonce): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">#<?= $annonce['id'] ?></td>
                        <td style="padding: 1rem; font-weight: 600;">
                            <?= htmlspecialchars($annonce['title']) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-secondary"><?= $annonce['visibility'] === 'public' ? 'Public' : 'Utilisateurs' ?></span>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                            $badgeColor = $annonce['status'] === 'published' ? '#10b981' : '#f59e0b'; 
                            ?>
                            <span style="color: <?= $badgeColor ?>; font-weight: 600; font-size: 0.85rem; padding: 0.2rem 0.5rem; background: <?= $badgeColor ?>22; border-radius: 4px;">
                                <?= ucfirst($annonce['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.8rem; color: var(--text-muted);">
                            <?php if($annonce['image_path']): ?>
                                <div style="color: var(--accent-color);">[Image]</div>
                            <?php endif; ?>
                            <?php if($annonce['attachment_path']): ?>
                                <div style="color: var(--accent-color);">[Pièce Jointe]</div>
                            <?php endif; ?>
                            <?php if(!$annonce['image_path'] && !$annonce['attachment_path']): ?>
                                Aucun
                            <?php endif; ?>
                        </td>
                        <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/annonce/edit/<?= $annonce['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Modifier</a>
                            <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/annonce/delete" method="POST" onsubmit="return confirm('Supprimer cette annonce ?');">
                                <input type="hidden" name="id" value="<?= $annonce['id'] ?>">
                                <button type="submit" class="btn" style="background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid rgba(239,68,68,0.5); padding: 0.4rem 0.8rem; font-size: 0.8rem;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune annonce publiée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
