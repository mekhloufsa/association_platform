<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 class="gradient-text">Dons Matériels Locaux</h1>
        <p style="color: var(--text-muted);">Gérez les collectes pour <?= htmlspecialchars($siege['association_name']) ?> - <?= htmlspecialchars($siege['wilaya_name']) ?></p>
    </div>
</div>

<div class="glass-panel" style="padding: 2rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text-main);">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                    <th style="padding: 1rem;">Donateur</th>
                    <th style="padding: 1rem;">Catégorie</th>
                    <th style="padding: 1rem;">Description</th>
                    <th style="padding: 1rem;">Statut</th>
                    <th style="padding: 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($donations)): foreach($donations as $d): ?>
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($d['prenom'] . ' ' . $d['nom']) ?></div>
                        </td>
                        <td style="padding: 1rem;"><span class="badge badge-secondary"><?= htmlspecialchars($d['category']) ?></span></td>
                        <td style="padding: 1rem; font-size: 0.9rem; max-width: 300px;"><?= htmlspecialchars($d['description']) ?></td>
                        <td style="padding: 1rem;">
                            <span class="badge badge-<?= $d['status'] === 'pending' ? 'warning' : ($d['status'] === 'collected' ? 'success' : 'info') ?>">
                                <?= ucfirst($d['status']) ?>
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/material-donation/<?= $d['id'] ?>" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Détails</a>
                            <?php if($d['status'] === 'pending'): ?>
                                <button class="btn btn-primary" onclick="openAcceptModal(<?= $d['id'] ?>)" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: #10b981;">Accepter</button>
                                <button class="btn btn-secondary" onclick="openRefuseModal(<?= $d['id'] ?>)" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: #ef4444; border: none;">Refuser</button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled style="padding: 0.4rem 0.8rem; font-size: 0.8rem; opacity: 0.5;">Traité</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">Aucun don matériel en attente pour votre antenne.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Accept Modal -->
<div id="acceptModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 1000; display: flex; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
    <div class="glass-panel" style="padding: 2.5rem; max-width: 500px; width: 90%; position: relative;">
        <button onclick="closeModals()" style="position: absolute; right: 1.5rem; top: 1.5rem; background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">✕</button>
        <h2 style="margin-bottom: 1.5rem; color: #10b981;">Accepter le don</h2>
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/donation/status" method="POST">
            <input type="hidden" name="id" id="accept_donation_id">
            <input type="hidden" name="status" value="scheduled">
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Proposer une date et heure de collecte</label>
                <input type="datetime-local" name="pickup_date" required style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Le citoyen verra cette date sur son tableau de bord.</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; background: #10b981;">Confirmer le rendez-vous</button>
        </form>
    </div>
</div>

<!-- Refuse Modal -->
<div id="refuseModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); align-items: center; justify-content: center; z-index: 1000; display: flex; opacity: 0; pointer-events: none; transition: opacity 0.3s;">
    <div class="glass-panel" style="padding: 2.5rem; max-width: 500px; width: 90%; position: relative;">
        <button onclick="closeModals()" style="position: absolute; right: 1.5rem; top: 1.5rem; background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">✕</button>
        <h2 style="margin-bottom: 1.5rem; color: #ef4444;">Refuser le don</h2>
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/donation/status" method="POST">
            <input type="hidden" name="id" id="refuse_donation_id">
            <input type="hidden" name="status" value="cancelled">
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Raison du refus (Message pour le citoyen)</label>
                <textarea name="manager_message" rows="4" required placeholder="Veuillez expliquer pourquoi ce don ne peut pas être accepté en ce moment..." style="width: 100%; padding: 0.8rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; resize: vertical;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-secondary" style="width: 100%; background: #ef4444; border: none;">Confirmer le refus</button>
        </form>
    </div>
</div>

<script>
function closeModals() {
    document.querySelectorAll('.modal').forEach(m => {
        m.style.opacity = '0';
        m.style.pointerEvents = 'none';
        setTimeout(() => m.style.display = 'none', 300);
    });
}

function openAcceptModal(id) {
    document.getElementById('accept_donation_id').value = id;
    const modal = document.getElementById('acceptModal');
    modal.style.display = 'flex';
    // Small delay to allow display flex to apply before transitioning opacity
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.pointerEvents = 'auto';
    }, 10);
}

function openRefuseModal(id) {
    document.getElementById('refuse_donation_id').value = id;
    const modal = document.getElementById('refuseModal');
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.style.pointerEvents = 'auto';
    }, 10);
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeModals();
    }
}
</script>
