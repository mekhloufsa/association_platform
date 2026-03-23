<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="gradient-text" style="margin-bottom: 2rem;">Créer une Association</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Remplissez ce formulaire pour soumettre votre projet d'association à l'administration.</p>

    <div class="glass-panel" style="padding: 2.5rem;">
        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/request/assoc-create" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Nom de l'Association</label>
                <input type="text" name="name" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Numéro de Carte Nationale (Responsable)</label>
                <input type="text" name="national_id_number" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Description et Objectifs</label>
                <textarea name="description" rows="5" required class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Logo de l'Association</label>
                <input type="file" name="logo" accept="image/*" class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Pièces Jointes (Statuts, PV d'AG...)</label>
                <input type="file" name="attachments[]" multiple class="glass-panel" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/dashboard" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Soumettre la demande</button>
            </div>
        </form>
    </div>
</div>
