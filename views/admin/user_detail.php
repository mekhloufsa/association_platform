<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails de l'Utilisateur</h1>
        <p style="color: var(--text-muted);">Consultation complète du profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/users" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
        <!-- Profile Card -->
        <div style="text-align: center; border-right: 1px solid var(--glass-border); padding-right: 2rem;">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; font-weight: bold;">
                <?= substr($user['nom'], 0, 1) ?>
            </div>
            <h2 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
            <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'user' ? 'primary' : 'success') ?>" style="font-size: 0.9rem;">
                <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
            </span>
            
            <div style="margin-top: 2rem; text-align: left; font-size: 0.9rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Email</label>
                    <span><?= htmlspecialchars($user['email']) ?></span>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Téléphone</label>
                    <span><?= htmlspecialchars($user['phone'] ?? 'Non renseigné') ?></span>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Wilaya</label>
                    <span><?= htmlspecialchars($user['wilaya_name'] ?? 'Non renseignée') ?></span>
                </div>
            </div>
        </div>

        <!-- Account Activity & Stats -->
        <div>
            <h3 style="margin-bottom: 1.5rem; color: var(--accent-color);">Résumé de l'activité</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">0</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Dons effectués</div>
                </div>
                <div class="glass-panel" style="padding: 1.5rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">0</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">Missions effectuées</div>
                </div>
            </div>

            <h3 style="margin-bottom: 1rem; color: var(--accent-color);">Informations complémentaires</h3>
            <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.02);">
                <table style="width: 100%; font-size: 0.9rem;">
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 0.8rem 0; color: var(--text-muted);">Date d'inscription</td>
                        <td style="padding: 0.8rem 0; text-align: right;"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 0.8rem 0; color: var(--text-muted);">ID Utilisateur</td>
                        <td style="padding: 0.8rem 0; text-align: right;">#<?= $user['id'] ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 0.8rem 0; color: var(--text-muted);">Statut du compte</td>
                        <td style="padding: 0.8rem 0; text-align: right;">
                            <span style="color: #10b981; font-weight: 600;">● <?= ucfirst($user['status'] ?? 'actif') ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <form action="<?= $basePath ?>/admin/user/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <button type="submit" class="btn btn-secondary" style="background: #ef4444; border: none; font-size: 0.85rem;">Supprimer cet utilisateur</button>
                </form>
            </div>
        </div>
    </div>
</div>
