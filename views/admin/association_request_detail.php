<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Détail de la demande d'association</h1>
        <p style="color: var(--text-muted);">Soumise le <?= date('d/m/Y', strtotime($request['created_at'])) ?></p>
    </div>
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/requests" class="btn btn-secondary">Retour aux demandes</a>
</div>

<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h2 style="font-size: 1.2rem; color: var(--accent-color); margin-bottom: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Informations sur l'Association</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
            <strong>Nom:</strong> <br><?= htmlspecialchars($request['name']) ?>
        </div>
        <div>
            <strong>Statut:</strong> <br>
            <span class="badge badge-<?= $request['status'] === 'pending' ? 'warning' : ($request['status'] === 'approved' ? 'success' : 'danger') ?>">
                <?= ucfirst($request['status']) ?>
            </span>
        </div>
    </div>
    <div style="margin-bottom: 1rem;">
        <strong>Description du projet:</strong> <br>
        <p style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; margin-top: 0.5rem; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($request['description']) ?></p>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h2 style="font-size: 1.2rem; color: var(--accent-color); margin-bottom: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Informations du Demandeur (Président)</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div>
            <strong style="color: var(--text-muted); font-size: 0.9rem;">Nom complet:</strong> <br>
            <span style="font-size: 1.1rem;"><?= htmlspecialchars($request['prenom'] . ' ' . $request['nom']) ?></span>
        </div>
        <div>
            <strong style="color: var(--text-muted); font-size: 0.9rem;">Email:</strong> <br>
            <a style="color: var(--accent-color); text-decoration: none;" href="mailto:<?= htmlspecialchars($request['email']) ?>"><?= htmlspecialchars($request['email']) ?></a>
        </div>
        <div>
            <strong style="color: var(--text-muted); font-size: 0.9rem;">Téléphone:</strong> <br>
            <span style="font-size: 1.1rem;"><?= htmlspecialchars($request['phone'] ?? 'Non renseigné') ?></span>
        </div>
        <div>
            <strong style="color: var(--text-muted); font-size: 0.9rem;">Carte d'Identité Nationale (CIN):</strong> <br>
            <span style="font-size: 1.1rem;"><?= htmlspecialchars($request['national_id_number']) ?></span>
        </div>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem; margin-bottom: 2rem;">
    <h2 style="font-size: 1.2rem; color: var(--accent-color); margin-bottom: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Pièces Jointes</h2>
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        <?php if (!empty($request['logo_path'])): ?>
            <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; text-align: center; width: 200px; border: 1px solid rgba(255,255,255,0.1);">
                <img src="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($request['logo_path']) ?>" alt="Logo" style="max-width: 100%; max-height: 150px; border-radius: 8px; margin-bottom: 0.5rem; object-fit: contain;">
                <br><strong style="font-size: 0.9rem;">Logo Proposé</strong>
            </div>
        <?php endif; ?>
        
        <?php 
        $attachments = !empty($request['attachments']) ? json_decode($request['attachments'], true) : [];
        if (!empty($attachments) && is_array($attachments)): 
            foreach ($attachments as $index => $file):
        ?>
            <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 150px; border: 1px solid rgba(255,255,255,0.1);">
                <span style="font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--accent-color);">📄</span>
                <span style="font-size: 0.8rem; text-align: center; margin-bottom: 0.8rem; word-break: break-all; color: var(--text-muted);">Document <?= $index + 1 ?></span>
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($file) ?>" target="_blank" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.3rem 0.6rem;">Ouvrir</a>
            </div>
        <?php 
            endforeach;
        endif; 
        ?>

        <?php if(empty($request['logo_path']) && empty($attachments)): ?>
            <p style="color: var(--text-muted); font-style: italic;">Aucune pièce jointe supplémentaire n'a été fournie avec cette demande.</p>
        <?php endif; ?>
    </div>
</div>

<?php if($request['status'] === 'pending'): ?>
<div class="glass-panel" style="padding: 2rem; text-align: center;">
    <h3 style="margin-bottom: 1.5rem; font-size: 1.2rem;">Action Requise</h3>
    <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/admin/association-request/review" method="POST" style="display: flex; justify-content: center; gap: 1rem;">
        <input type="hidden" name="id" value="<?= $request['id'] ?>">
        <button type="submit" name="action" value="approve" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">Accepter la demande</button>
        <button type="submit" name="action" value="reject" class="btn btn-secondary" style="padding: 0.8rem 2rem; font-size: 1rem; border: 1px solid rgba(239, 68, 68, 0.5); color: rgba(239, 68, 68, 1);">Refuser la demande</button>
    </form>
</div>
<?php endif; ?>
