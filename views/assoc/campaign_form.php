<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 2rem;">Créer une Campagne</h1>
    
    <div class="glass-panel" style="padding: 2.5rem;">
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/add-campaign" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Titre de la campagne</label>
                <input type="text" name="title" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description détaillée</label>
                <textarea name="description" rows="5" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;"></textarea>
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
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Type de Campagne</label>
                    <select name="campaign_type" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                        <option value="local" style="color: black;">Locale</option>
                        <option value="national" style="color: black;">Nationale</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Type de Besoin</label>
                    <select name="need_type" id="need_type" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;" onchange="toggleNeedTypeFields()">
                        <option value="personnel" style="color: black;">Personnel (Bénévoles)</option>
                        <option value="argent" style="color: black;">Financier (Argent)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Lieu / Zone d'action</label>
                    <input type="text" name="location" placeholder="Ex: Alger, Oran, National..." class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div id="volunteers_field">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Max Bénévoles</label>
                    <input type="number" name="max_volunteers" placeholder="Illimité si vide" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div id="financial_field" style="display: none;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Objectif Financier (DZD)</label>
                    <input type="number" name="financial_goal" placeholder="Objectif en DZD" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/campaigns" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Publier la Campagne</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleNeedTypeFields() {
    const needType = document.getElementById('need_type').value;
    const volField = document.getElementById('volunteers_field');
    const finField = document.getElementById('financial_field');
    
    if (needType === 'argent') {
        volField.style.display = 'none';
        finField.style.display = 'block';
    } else {
        volField.style.display = 'block';
        finField.style.display = 'none';
    }
}

// Initial call to set correct state
document.addEventListener('DOMContentLoaded', toggleNeedTypeFields);
</script>
