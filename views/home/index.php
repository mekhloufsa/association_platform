<?php $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
<!-- Hero Section -->
<section class="hero" style="background-image: url('<?= $basePath ?>/images/hero_bg.png');">
    <div class="hero-content">
        <span class="hero-label">Service Public National</span>
        <h1>Plateforme Solidaire <br>des Associations</h1>
        <p class="hero-subtitle">Un espace centralisé pour soutenir les initiatives locales, faciliter les dons et promouvoir le bénévolat au service de l'intérêt général.</p>
        <div class="hero-actions">
            <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'guest'): ?>
                <a href="<?= $basePath ?>/associations" class="btn btn-primary">Annuaire des Associations</a>
                <a href="<?= $basePath ?>/login" class="btn btn-secondary">Accéder à mon espace</a>
            <?php elseif($_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= $basePath ?>/admin/dashboard" class="btn btn-primary">Mon Tableau de bord Admin</a>
            <?php else: ?>
                <a href="<?= $basePath ?>/dashboard/help-request" class="btn btn-primary">Faire une demande d'aide</a>
                <a href="<?= $basePath ?>/associations" class="btn btn-secondary">Consulter les associations</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Quick Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <h4><?= htmlspecialchars($stats['associations']) ?></h4>
            <p>Associations Actives</p>
        </div>
        <div class="stat-item">
            <h4><?= number_format((float)$stats['fonds'], 0, ',', ' ') ?> DZD</h4>
            <p>Fonds Collectés</p>
        </div>
        <div class="stat-item">
            <h4><?= htmlspecialchars($stats['benevoles']) ?></h4>
            <p>Bénévoles Engagés</p>
        </div>
        <div class="stat-item">
            <h4><?= htmlspecialchars($stats['campagnes']) ?></h4>
            <p>Campagnes Réussies</p>
        </div>
    </div>
</section>

<!-- Features Grid -->
<div class="features-container">
    <div class="container">
        <div class="section-header">
            <h2>Services en Ligne</h2>
            <p>Accédez rapidement aux différents services offerts par la plateforme pour contribuer au développement national.</p>
        </div>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Dons Sécurisés</h3>
                <p>Participez aux campagnes de financement ou faites des dons matériels aux associations de votre choix en toute confiance.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3>Bénévolat</h3>
                <p>Découvrez les activités organisées par les sièges locaux et engagez-vous sur le terrain avec votre communauté.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3>Demandes d'Aide</h3>
                <p>Soumettez directement une demande de soutien matériel ou financier si vous êtes dans le besoin.</p>
            </div>
        </div>
    </div>
</div>

<!-- Info Section (Announcements preview) -->
<section class="info-section">
    <div class="container">
        <div class="section-header">
            <h2>Actualités Récentes</h2>
            <p>Retrouvez les dernières informations concernant les campagnes de volontariat, l'ouverture de dons et les réformes associatives.</p>
        </div>
        
        <div class="news-grid">
            <?php if (!empty($recentAnnonces)): ?>
                <?php foreach($recentAnnonces as $annonce): ?>
                <div class="news-card">
                    <div class="news-content">
                        <span class="news-date"><?= date('d/m/Y', strtotime($annonce['published_at'])) ?></span>
                        <h3 class="news-title"><a href="<?= $basePath ?>/annonce/<?= $annonce['id'] ?>"><?= htmlspecialchars($annonce['title']) ?></a></h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem;">
                            <?= htmlspecialchars(mb_strimwidth(strip_tags($annonce['content']), 0, 100, "...")) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-muted); width: 100%;">Aucune annonce récente pour le moment.</p>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="<?= $basePath ?>/annonces" class="btn btn-secondary">Toutes les annonces</a>
        </div>
    </div>
</section>
