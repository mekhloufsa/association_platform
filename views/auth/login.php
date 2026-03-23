<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div class="auth-container" style="max-width: 400px; margin: 2rem auto; animation: floatUp 0.5s ease-out;">
    <div class="glass-panel" style="padding: 2.5rem;">
        <h2 class="gradient-text" style="text-align: center; margin-bottom: 2rem;">Connexion</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= $basePath ?>/login" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
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

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Se Connecter</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Pas encore de compte ? <a href="<?= $basePath ?>/register" style="color: var(--accent-color);">Inscrivez-vous ici</a>
        </p>
    </div>
</div>
