<?php
// Configuration temporaire du basePath pour les assets
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura - Plateforme des Associations</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- Main Style -->
    <link rel="stylesheet" href="<?= $basePath ?>/css/style.css">
</head>
<body>
    <?php 
        $active_space = $_SESSION['active_space'] ?? 'citizen';
        $user_role = $_SESSION['user_role'] ?? 'guest';
        $user_id = $_SESSION['user_id'] ?? null;
        
        // Define dashboards
        $citizenDashboard = $basePath . '/dashboard';
        $assocDashboard = $basePath . '/assoc/dashboard';
        $siegeDashboard = $basePath . '/siege/dashboard';
        $adminDashboard = $basePath . '/admin/dashboard';
    ?>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo-container">
                <a href="<?= $basePath ?>/" class="logo">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem; color: var(--primary-color);">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Aura
                </a>
            </div>
            
            <div class="nav-links">
                <?php if ($user_role === 'admin'): ?>
                    <a href="<?= $adminDashboard ?>">Espace Admin</a>
                <?php elseif ($active_space === 'citizen' || !$user_id): ?>
                    <a href="<?= $basePath ?>/">Accueil</a>
                    <a href="<?= $basePath ?>/annonces">Annonces</a>
                    <a href="<?= $basePath ?>/associations">Associations</a>
                <?php endif; ?>
            </div>

            <div class="nav-actions">
                <?php if($user_id): ?>
                    <?php if ($user_role !== 'admin'): ?>
                    <div class="switch-group">
                        <?php if ($user_role !== 'admin'): ?>
                        <a href="<?= $basePath ?>/dashboard/switch?to=citizen" 
                           class="btn <?= $active_space === 'citizen' ? 'btn-primary' : 'btn-secondary' ?>" 
                           style="padding: 0.25rem 0.75rem; font-size: 0.75rem; border: none; box-shadow: none;">Citoyen</a>
                        <?php endif; ?>
                        
                        <?php if ($user_role === 'president_assoc'): ?>
                            <a href="<?= $basePath ?>/dashboard/switch?to=association" 
                               class="btn <?= $active_space === 'association' ? 'btn-primary' : 'btn-secondary' ?>" 
                               style="padding: 0.25rem 0.75rem; font-size: 0.75rem; border: none; box-shadow: none;">Association</a>
                        <?php endif; ?>

                        <?php if ($user_role === 'president_siege'): ?>
                            <a href="<?= $basePath ?>/dashboard/switch?to=siege" 
                               class="btn <?= $active_space === 'siege' ? 'btn-primary' : 'btn-secondary' ?>" 
                               style="padding: 0.25rem 0.75rem; font-size: 0.75rem; border: none; box-shadow: none;">Siège</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <a href="<?= $basePath ?>/logout" class="btn btn-secondary" style="border-color: #ef4444; color: #ef4444; padding: 0.5rem 1rem;">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $basePath ?>/login" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">Connexion</a>
                <?php endif; ?>
            </div>
            
            <div class="burger-menu">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Flash Messages -->
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="flash-message flash-<?= $_SESSION['flash']['type'] ?>">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?> 
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col" style="grid-column: span 2;">
                <h3 style="display: flex; align-items: center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem; color: var(--accent-color);">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Aura
                </h3>
                <p>Plateforme nationale de coordination et de soutien aux associations. Facilitons l'engagement citoyen et la solidarité de manière transparente et efficace.</p>
            </div>
            <div class="footer-col">
                <h3>Navigation</h3>
                <ul class="footer-links">
                    <li><a href="<?= $basePath ?>/">Accueil</a></li>
                    <li><a href="<?= $basePath ?>/associations">Annuaire des Associations</a></li>
                    <li><a href="<?= $basePath ?>/annonces">Dernières Annonces</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact & Aide</h3>
                <ul class="footer-links">
                    <li><a href="#">Support technique</a></li>
                    <li><a href="#">Foire aux questions</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Aura - Plateforme des Associations. Projet officiel.</p>
        </div>
    </footer>

    <!-- Main Script -->
    <script src="<?= $basePath ?>/js/main.js"></script>
</body>
</html>
