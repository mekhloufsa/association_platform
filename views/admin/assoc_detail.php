<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détails de l'Association</h1>
        <p style="color: var(--text-muted);">Gestion et consultation de <?= htmlspecialchars($association['name']) ?></p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/associations" class="btn btn-secondary">Retour à la liste</a>
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association/validate" method="POST" style="display: inline;">
            <input type="hidden" name="assoc_id" value="<?= $association['id'] ?>">
            <?php if($association['national_account_status'] === 'approved'): ?>
                <button type="submit" name="status" value="rejected" class="btn btn-secondary" style="background: #ef4444; border: none;">Suspendre</button>
            <?php else: ?>
                <button type="submit" name="status" value="approved" class="btn btn-primary" style="background: #10b981;">Activer</button>
            <?php endif; ?>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
    <!-- Left Column: Info Card -->
    <div>
        <div class="glass-panel" style="padding: 1.5rem; position: sticky; top: 2rem;">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 12px; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; font-weight: bold; overflow: hidden; border: 2px solid var(--glass-border);">
                <?php if (!empty($association['logo_path'])): ?>
                    <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($association['logo_path']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <?= substr($association['name'], 0, 1) ?>
                <?php endif; ?>
            </div>
            
            <h3 style="text-align: center; margin-bottom: 0.5rem;"><?= htmlspecialchars($association['name']) ?></h3>
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <span class="badge badge-<?= $association['national_account_status'] === 'approved' ? 'success' : 'warning' ?>">
                    Statut: <?= ucfirst($association['national_account_status']) ?>
                </span>
            </div>

            <div style="font-size: 0.9rem; display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Président</label>
                    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/user/<?= $association['president_user_id'] ?>" style="color: var(--accent-color); text-decoration: none;">
                        <?= htmlspecialchars($association['president_prenom'] . ' ' . $association['president_nom']) ?>
                    </a>
                </div>
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Email Président</label>
                    <span><?= htmlspecialchars($association['president_email']) ?></span>
                </div>
                <div>
                    <label style="color: var(--text-muted); display: block; font-size: 0.8rem;">Date de création</label>
                    <span><?= date('d/m/Y', strtotime($association['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Details & Sieges -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-panel" style="padding: 2rem;">
            <h3 style="margin-bottom: 1rem; color: var(--accent-color);">Description</h3>
            <p style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6; color: var(--text-main);">
                <?= htmlspecialchars($association['description']) ?>
            </p>
        </div>

        <div class="glass-panel" style="padding: 2rem;">
            <h3 style="margin-bottom: 1.5rem; color: var(--accent-color); display: flex; justify-content: space-between; align-items: center;">
                Sièges Locaux (<?= count($sieges) ?>)
            </h3>
            
            <?php if(!empty($sieges)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    <?php foreach($sieges as $s): ?>
                        <div class="glass-panel" style="padding: 1.5rem; background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div style="font-weight: 600; color: var(--text-main);">Wilaya: <?= htmlspecialchars($s['wilaya_name']) ?></div>
                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/siege/<?= $s['id'] ?>" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Voir</a>
                            </div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                                <i style="display: block; font-style: normal; margin-bottom: 0.25rem;">📍 <?= htmlspecialchars($s['address']) ?></i>
                                <i style="display: block; font-style: normal;">👤 Responsable: <?= $s['manager_nom'] ? htmlspecialchars($s['manager_prenom'] . ' ' . $s['manager_nom']) : '<span style="color: #ef4444;">Aucun</span>' ?></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Aucun siège créé pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
