<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Gestion des Sièges (Antennes)</h1>
        <p style="color: var(--text-muted);">Administrez vos bureaux locaux à travers le pays.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege-requests" class="btn btn-secondary">Candidatures en attente</a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/add-siege" class="btn btn-primary">+ Ajouter un Siège</a>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Wilaya</th>
                    <th style="padding: 1rem;">Adresse</th>
                    <th style="padding: 1rem;">Responsable</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($sieges)): foreach($sieges as $s): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($s['wilaya_name']) ?></div>
                        </td>
                        <td style="padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                            <?= htmlspecialchars($s['address']) ?>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= $s['manager_nom'] ? htmlspecialchars($s['manager_prenom'] . ' ' . $s['manager_nom']) : '<span style="color: var(--text-muted);">Non assigné</span>' ?>
                        </td>
                        <td style="padding: 1rem; text-align: right; display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/detail/<?= $s['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Voir Détails</a>
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/edit/<?= $s['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Modifier</a>
                            
                            <?php if($s['manager_user_id']): ?>
                                <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/remove-manager" method="POST" style="display:inline;" onsubmit="return confirm('Retirer le responsable ?');">
                                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: orange; color: orange;">Retirer Responsable</button>
                                </form>
                            <?php endif; ?>

                            <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/delete" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce siège définitivement ?');">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: red; color: red;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune antenne locale enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
