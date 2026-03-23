<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <a href="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/dashboard" style="color: var(--accent-color); text-decoration: none; display: inline-block; margin-bottom: 2rem;">← Retour au tableau de bord</a>

    <div class="glass-panel" style="padding: 2.5rem;">
        <h1 class="gradient-text" style="margin-bottom: 1.5rem;">Paramètres de l'Association</h1>
        <p style="color: var(--text-muted); margin-bottom: 2.5rem;">Personnalisez l'expérience des donateurs pour votre association.</p>

        <form action="<?= str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) ?>/assoc/settings" method="POST">
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="thank_you_message" style="display: block; margin-bottom: 1rem; font-weight: 600;">Message de remerciement personnalisé</label>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Ce message sera affiché aux citoyens après chaque don (financier ou matériel) effectué à votre association ou l'un de ses sièges.</p>
                <textarea id="thank_you_message" name="thank_you_message" rows="6" 
                          style="width: 100%; padding: 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 12px; color: white; resize: vertical; font-family: inherit;"
                          placeholder="Ex: Merci infiniment pour votre soutien ! Grâce à vous, nous pourrons distribuer 50 repas supplémentaires cette semaine."><?= htmlspecialchars($association['thank_you_message'] ?? '') ?></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
