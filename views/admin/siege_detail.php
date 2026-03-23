<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails du Siège</h1>
        <p style="color: var(--text-muted);">Consultation du siège de <?= htmlspecialchars($siege['association_name']) ?> - Wilaya: <?= htmlspecialchars($siege['wilaya_name']) ?></p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/associations" class="btn btn-secondary">← Retour à la liste</a>
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/<?= $siege['association_id'] ?>" class="btn btn-secondary">Voir l'Association</a>
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/siege/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce siège ?');">
            <input type="hidden" name="id" value="<?= $siege['id'] ?>">
            <button type="submit" class="btn btn-secondary" style="background: #ef4444; border: none;">Supprimer</button>
        </form>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
        <!-- Left: Basic Info -->
        <div>
            <h3 style="margin-bottom: 1.5rem; color: var(--accent-color);">Informations Générales</h3>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 0.25rem;">Association Parente</label>
                    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/<?= $siege['association_id'] ?>" style="color: var(--primary-color); font-weight: 600; text-decoration: none; font-size: 1.1rem;">
                        <?= htmlspecialchars($siege['association_name']) ?>
                    </a>
                </div>
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 0.25rem;">Wilaya d'implantation</label>
                    <span style="font-size: 1rem; font-weight: 500;"><?= htmlspecialchars($siege['wilaya_name']) ?> (<?= $siege['wilaya_id'] ?>)</span>
                </div>
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 0.25rem;">Adresse précise</label>
                    <p style="font-size: 0.95rem; line-height: 1.4;"><?= htmlspecialchars($siege['address']) ?></p>
                </div>
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem; margin-bottom: 0.25rem;">Date d'ouverture</label>
                    <span><?= date('d/m/Y', strtotime($siege['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <!-- Right: Management Info -->
        <div>
            <h3 style="margin-bottom: 1.5rem; color: var(--accent-color);">Responsable Actuel</h3>
            <?php if ($siege['manager_user_id']): ?>
                <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                        <?= substr($siege['manager_nom'], 0, 1) ?>
                    </div>
                    <div>
                        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/user/<?= $siege['manager_user_id'] ?>" style="color: white; font-weight: 600; text-decoration: none; display: block; font-size: 1.1rem;">
                            <?= htmlspecialchars($siege['manager_prenom'] . ' ' . $siege['manager_nom']) ?>
                        </a>
                        <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($siege['manager_email']) ?></span>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Actions de gestion</h4>
                    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/siege/remove-manager" method="POST" onsubmit="return confirm('Voulez-vous vraiment retirer ce responsable ?');">
                        <input type="hidden" name="id" value="<?= $siege['id'] ?>">
                        <button type="submit" class="btn btn-secondary" style="font-size: 0.8rem; border-color: #ef4444; color: #ef4444;">Retirer le responsable</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="glass-panel" style="padding: 2rem; text-align: center; border: 1px dashed var(--glass-border);">
                    <p style="color: #ef4444; margin-bottom: 0.5rem; font-weight: 600;">Ce siège n'a pas de responsable.</p>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Les demandes d'aide ne seront pas affichées dans l'annuaire pour cette wilaya tant qu'un responsable n'est pas assigné.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
