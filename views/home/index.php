<!-- Hero Section -->
<section class="hero glass-panel">
    <div class="hero-content">
        <h1 class="gradient-text"><?= htmlspecialchars($title) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($description) ?></p>
        <div class="hero-actions">
            <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                <a href="associations" class="btn btn-primary">Trouver une association</a>
                <a href="dashboard/help-request" class="btn btn-secondary">Demander de l'aide</a>
            <?php else: ?>
                <a href="admin/dashboard" class="btn btn-primary">Mon Tableau de bord Admin</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Quick Stats / Features -->
<section class="features">
    <div class="feature-grid">
        <div class="feature-card glass-panel">
            <h3>Dons Simplifiés</h3>
            <p>Soutenez vos associations favorites via dons matériels ou paiements sécurisés.</p>
        </div>
        <div class="feature-card glass-panel">
            <h3>Bénévolat</h3>
            <p>Rejoignez des campagnes locales et faites la différence sur le terrain.</p>
        </div>
        <div class="feature-card glass-panel">
            <h3>Transparence</h3>
            <p>Suivez l'état de vos dons et participez activement au développement communautaire.</p>
        </div>
    </div>
</section>
