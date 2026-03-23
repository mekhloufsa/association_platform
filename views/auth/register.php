<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div class="auth-container" style="max-width: 500px; margin: 2rem auto; animation: floatUp 0.5s ease-out;">
    <div class="glass-panel" style="padding: 2.5rem;">
        <h2 class="gradient-text" style="text-align: center; margin-bottom: 2rem;">Créer un Compte</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= $basePath ?>/register" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="display: flex; gap: 1rem;">
                <div class="form-group" style="flex: 1;">
                    <label for="prenom" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required 
                        style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="nom" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Nom</label>
                    <input type="text" id="nom" name="nom" required 
                        style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
                </div>
            </div>

            <div class="form-group">
                <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Adresse Email</label>
                <input type="email" id="email" name="email" required 
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>
            
            <div class="form-group">
                <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Mot de Passe</label>
                <input type="password" id="password" name="password" required 
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div class="form-group">
                <label for="phone" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Téléphone (Optionnel)</label>
                <input type="tel" id="phone" name="phone" 
                       style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white;">
            </div>

            <div class="form-group">
                <label for="wilaya_id" style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Wilaya</label>
                <select id="wilaya_id" name="wilaya_id" required
                        style="width: 100%; padding: 0.75rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; color: white; appearance: none;">
                    <option value="">Sélectionnez votre Wilaya</option>
                    <?php if(!empty($wilayas)): foreach($wilayas as $wilaya): ?>
                        <option value="<?= $wilaya['id'] ?>"><?= $wilaya['id'] ?> - <?= htmlspecialchars($wilaya['name']) ?></option>
                    <?php endforeach; else: ?>
                        <option value="16" selected>16 - Alger (Par défaut)</option>
                    <?php endif; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">S'inscrire</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Déjà un compte ? <a href="<?= $basePath ?>/login" style="color: var(--accent-color);">Connectez-vous</a>
        </p>
    </div>
</div>
