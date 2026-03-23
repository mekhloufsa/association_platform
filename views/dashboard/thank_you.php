<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<div style="max-width: 700px; margin: 0 auto; padding: 4rem 2rem; text-align: center;">
    <div class="glass-panel" style="padding: 3rem; animation: float 6s ease-in-out infinite;">
        <div style="font-size: 5rem; margin-bottom: 2rem;">❤️</div>
        <h1 class="gradient-text" style="font-size: 2.5rem; margin-bottom: 1.5rem;">Merci infiniment !</h1>
        
        <div style="background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 20px; border: 1px solid var(--glass-border); margin-bottom: 2.5rem;">
            <p style="font-size: 1.2rem; line-height: 1.8; color: var(--text-main); font-style: italic;">
                "<?= nl2br(htmlspecialchars($message)) ?>"
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="<?= $basePath ?>/dashboard" class="btn btn-primary" style="padding: 1rem 2rem; font-weight: 600;">Retour à mon espace</a>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Votre générosité fait la différence.</p>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}
</style>
