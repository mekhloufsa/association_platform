<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Demandes d'Aide Locales</h1>
        <p style="color: var(--text-muted);">Gestion des dossiers pour <?= htmlspecialchars($siege['association_name']) ?> - Wilaya: <?= htmlspecialchars($siege['wilaya_name']) ?></p>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Demandeur</th>
                    <th style="padding: 1rem;">Sujet</th>
                    <th style="padding: 1rem;">Date</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($requests)): foreach($requests as $r): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($r['prenom'] . ' ' . $r['nom']) ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($r['email']) ?></div>
                        </td>
                        <td style="padding: 1rem;"><?= htmlspecialchars($r['subject']) ?></td>
                        <td style="padding: 1rem; font-size: 0.9rem;"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-<?= $r['status'] === 'pending' ? 'warning' : ($r['status'] === 'accepted' ? 'success' : 'danger') ?>">
                                <?= ucfirst($r['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <button class="btn btn-secondary" onclick="toggleDetails(<?= $r['id'] ?>)" style="padding: 0.4rem 0.6rem; font-size: 0.75rem;">Voir Détails</button>
                                <?php if($r['status'] === 'pending'): ?>
                                    <button class="btn btn-primary" onclick="toggleActionForm(<?= $r['id'] ?>, 'accept')" style="padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #10b981;">Décider</button>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Traité</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <!-- Detail Row -->
                    <tr id="details-<?= $r['id'] ?>" style="display: none; background: rgba(255,255,255,0.02);">
                        <td colspan="5" style="padding: 1.5rem; border-bottom: 2px solid var(--accent-color);">
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <h4 style="color: var(--accent-color); margin-bottom: 0.5rem;">Description complète</h4>
                                    <p style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;"><?= htmlspecialchars($r['description']) ?></p>
                                </div>
                                <div>
                                    <h4 style="color: var(--accent-color); margin-bottom: 0.5rem;">Pièces Jointes</h4>
                                    <?php 
                                    $attachments = json_decode($r['attachments'] ?? '[]', true);
                                    if (!empty($attachments)): 
                                    ?>
                                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                            <?php foreach($attachments as $file): ?>
                                                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/<?= htmlspecialchars($file) ?>" target="_blank" class="glass-panel" 
                                                   style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; color: white; text-decoration: none; font-size: 0.85rem; border: 1px solid var(--glass-border);">
                                                    <span style="font-size: 1.2rem;">📄</span>
                                                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= basename($file) ?></span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p style="color: var(--text-muted); font-size: 0.85rem;">Aucune pièce jointe.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Decision Form inside details -->
                            <div id="form-<?= $r['id'] ?>" class="glass-panel" style="display: none; padding: 1.5rem; border: 1px solid var(--accent-color); animation: fadeIn 0.3s ease;">
                                <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/help-request/status" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="status" id="status-<?= $r['id'] ?>" value="">
                                    
                                    <h3 id="title-<?= $r['id'] ?>" class="gradient-text" style="font-size: 1.1rem; margin: 0;"></h3>
                                    
                                    <div id="accept-fields-<?= $r['id'] ?>" style="display: none;">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                            <div>
                                                <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Date du rendez-vous</label>
                                                <input type="date" name="appointment_date" class="glass-panel" style="width: 100%; padding: 0.6rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);">
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Heure</label>
                                                <input type="time" name="appointment_time" class="glass-panel" style="width: 100%; padding: 0.6rem; background: rgba(255,255,255,0.05); color: var(--text-main); border: 1px solid var(--glass-border);">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="reject-fields-<?= $r['id'] ?>" style="display: none;">
                                        <label style="display: block; font-size: 0.9rem; margin-bottom: 0.5rem;">Motif du refus (Obligatoire) :</label>
                                        <textarea name="refusal_message" id="refusal-<?= $r['id'] ?>" placeholder="Expliquez pourquoi la demande est refusée..." style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); color: white; border: 1px solid var(--glass-border); border-radius: 4px;"></textarea>
                                    </div>

                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button type="button" onclick="toggleActionForm(<?= $r['id'] ?>, '')" class="btn btn-secondary" style="font-size: 0.8rem;">Annuler</button>
                                        <button type="submit" class="btn btn-primary" style="font-size: 0.8rem;">Confirmer la décision</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucune demande d'aide trouvée pour votre antenne.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleDetails(id) {
    const row = document.getElementById('details-' + id);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}

function toggleActionForm(id, type) {
    const form = document.getElementById('form-' + id);
    const detailRow = document.getElementById('details-' + id);
    
    if (!type) {
        form.style.display = 'none';
        return;
    }
    
    // Auto-open detail row if it's closed
    detailRow.style.display = 'table-row';
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
