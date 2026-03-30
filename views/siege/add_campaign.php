<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 1rem;">Proposer une Campagne Locale</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Cette campagne sera soumise au bureau national de votre association pour validation avant d'être publiée.</p>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/add-campaign" method="POST" enctype="multipart/form-data">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Titre de la campagne</label>
                <input type="text" name="title" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description détaillée</label>
                <textarea name="description" rows="5" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;"></textarea>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Affiche ou Photo descriptive (obligatoire)</label>
                <input type="file" name="image" accept="image/*" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Date de début</label>
                    <input type="date" name="start_date" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Date de fin</label>
                    <input type="date" name="end_date" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Lieu / Zone d'action de votre antenne</label>
                    <input type="text" name="location" placeholder="Ex: Qartier X, ..." class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Objectif</label>
                    <select name="need_type" id="need_type" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" onchange="toggleNeedTypeFields()">
                        <option value="personnel" style="color: black;">Recrutement de Bénévoles</option>
                        <option value="financial" style="color: black;">Collecte de Fonds</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label id="target_label" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Nombre maximum de bénévoles</label>
                <input type="number" name="target" id="target_input" placeholder="Illimité si laissé vide" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/siege/campaigns" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Soumettre pour validation</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleNeedTypeFields() {
    const needType = document.getElementById('need_type').value;
    const label = document.getElementById('target_label');
    const input = document.getElementById('target_input');
    
    if (needType === 'financial') {
        label.innerText = 'Objectif Financier (DZD) *';
        input.placeholder = 'Ex: 500000';
        input.required = true;
    } else {
        label.innerText = 'Nombre maximum de bénévoles';
        input.placeholder = 'Illimité si laissé vide';
        input.required = false;
    }
}

// Initial call to set correct state
document.addEventListener('DOMContentLoaded', toggleNeedTypeFields);
</script>
