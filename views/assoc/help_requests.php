<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="gradient-text">Demandes d'Aide Reçues</h1>
    <a href="<?= $basePath ?>/assoc/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Citoyen</th>
                    <th style="padding: 1rem;">Sujet</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($requests)): foreach($requests as $req): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem; font-size: 0.9rem;">
                            <?= date('d/m/Y', strtotime($req['created_at'])) ?>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($req['prenom'] . ' ' . $req['nom']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($req['email']) ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="font-weight: 500;"><?= htmlspecialchars($req['subject']) ?></div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);"><?= mb_strimwidth(htmlspecialchars($req['description']), 0, 50, "...") ?></div>
                        </td>
                        <td style="padding: 1rem;">
                            <?php 
                                $statusColor = '#f59e0b';
                                if($req['status'] === 'accepted') $statusColor = '#10b981';
                                if($req['status'] === 'rejected') $statusColor = '#ef4444';
                            ?>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; background: <?= $statusColor ?>22; color: <?= $statusColor ?>; border: 1px solid <?= $statusColor ?>55;">
                                <?= ucfirst($req['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <?php if($req['status'] === 'pending'): ?>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end;">
                                    <button class="btn btn-primary" onclick="toggleActionForm(<?= $req['id'] ?>, 'accept')" style="padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #10b981;">Accepter</button>
                                    <button class="btn btn-secondary" onclick="toggleActionForm(<?= $req['id'] ?>, 'reject')" style="padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #ef4444; border: none;">Refuser</button>
                                </div>

                                <div id="form-<?= $req['id'] ?>" class="glass-panel" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; padding: 2rem; width: 400px; border: 1px solid var(--accent-color);">
                                    <form action="<?= $basePath ?>/assoc/help-request/status" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="status" id="status-<?= $req['id'] ?>" value="">
                                        
                                        <h3 id="title-<?= $req['id'] ?>" class="gradient-text"></h3>
                                        <div id="accept-fields-<?= $req['id'] ?>" style="display: none; margin-top: 1rem;">
                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                                <div>
                                                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Date du rendez-vous</label>
                                                    <input type="date" name="appointment_date" class="glass-panel" style="width: 100%; padding: 0.6rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);">
                                                </div>
                                                <div>
                                                    <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Heure</label>
                                                    <input type="time" name="appointment_time" class="glass-panel" style="width: 100%; padding: 0.6rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);">
                                                </div>
                                            </div>
                                            <p style="font-size: 0.75rem; color: var(--text-muted);">Les citoyens recevront ces détails dans leur tableau de bord.</p>
                                        </div>

                                        <div id="reject-fields-<?= $req['id'] ?>" style="display: none;">
                                            <label style="display: block; font-size: 0.9rem; margin-bottom: 0.5rem;">Motif du refus (Obligatoire) :</label>
                                            <textarea name="refusal_message" id="refusal-<?= $req['id'] ?>" placeholder="Expliquez pourquoi la demande est refusée..." style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--glass-border); border-radius: 4px;"></textarea>
                                        </div>

                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1rem;">
                                            <button type="button" onclick="toggleActionForm(<?= $req['id'] ?>, '')" class="btn btn-secondary" style="font-size: 0.8rem;">Annuler</button>
                                            <button type="submit" class="btn btn-primary" style="font-size: 0.8rem;">Confirmer</button>
                                        </div>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.8rem;">Traité</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune demande d'aide reçue.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleActionForm(id, type) {
    const form = document.getElementById('form-' + id);
    if (!type) {
        form.style.display = 'none';
        return;
    }
    
    form.style.display = 'block';
    document.getElementById('status-' + id).value = (type === 'accept' ? 'accepted' : 'rejected');
    document.getElementById('title-' + id).innerText = (type === 'accept' ? 'Accepter la demande' : 'Refuser la demande');
    document.getElementById('accept-fields-' + id).style.display = (type === 'accept' ? 'block' : 'none');
    document.getElementById('reject-fields-' + id).style.display = (type === 'reject' ? 'block' : 'none');
    
    if (type === 'reject') {
        document.getElementById('refusal-' + id).setAttribute('required', 'required');
    } else {
        document.getElementById('refusal-' + id).removeAttribute('required');
    }
}
</script>
