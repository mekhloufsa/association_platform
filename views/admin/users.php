<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Gestion des Utilisateurs</h1>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<!-- Filters & Search -->
<div class="glass-panel" style="padding: 1.5rem; margin-bottom: 2rem;">
    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/users" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
        <div style="flex: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Rechercher</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, email..." 
                   style="width: 100%; padding: 0.7rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
        </div>
        <div>
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Rôle</label>
            <select name="role" style="padding: 0.7rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                <option value="">Tous les rôles</option>
                <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Citoyen</option>
                <option value="president_assoc" <?= $role === 'president_assoc' ? 'selected' : '' ?>>Président</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">Statut</label>
            <select name="status" style="padding: 0.7rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                <option value="">Tous les statuts</option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Actif</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactif</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 0.7rem 1.5rem;">Filtrer</button>
        <?php if($search || $role || $status): ?>
            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/users" class="btn btn-secondary" style="padding: 0.7rem 1rem;">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Nom & Prénom</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Rôle</th>
                    <th style="padding: 1rem;">Wilaya</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)): foreach($users as $user): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem; font-weight: 600;">
                            <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                        </td>
                        <td style="padding: 1rem; color: var(--text-muted);">
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $roleColor = '#64ffda';
                                if($user['role'] === 'admin') $roleColor = '#ef4444';
                                if($user['role'] === 'president_assoc') $roleColor = '#3b82f6';
                            ?>
                            <span style="font-size: 0.85rem; color: <?= $roleColor ?>;">
                                <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-muted);">
                            <?= htmlspecialchars($user['wilaya_name'] ?? 'N/A') ?>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/user/<?= $user['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">Détails</a>
                                <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/user/delete" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #ef4444; border: none;">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
