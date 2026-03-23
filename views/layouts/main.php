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
    <!-- Navigation (Glassmorphism) -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="<?= $basePath ?>/" class="logo">Aura</a>
            <div class="nav-links">
                <a href="<?= $basePath ?>/">Accueil</a>
                <?php if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                    <a href="<?= $basePath ?>/annonces">Annonces</a>
                    <a href="<?= $basePath ?>/associations">Associations</a>
                <?php else: ?>
                    <a href="<?= $basePath ?>/annonces">Toutes les Annonces</a>
                    <a href="<?= $basePath ?>/admin/associations">Gérer Associations</a>
                    <a href="<?= $basePath ?>/admin/users">Gérer Utilisateurs</a>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php 
                        $dashboardLink = '/dashboard';
                        if($_SESSION['user_role'] === 'admin') $dashboardLink = '/admin/dashboard';
                        elseif($_SESSION['user_role'] === 'president_assoc') $dashboardLink = '/assoc/dashboard';
                        elseif($_SESSION['user_role'] === 'president_siege') $dashboardLink = '/siege/dashboard';
                    ?>
                    <a href="<?= $basePath ?><?= $dashboardLink ?>" class="btn btn-secondary">Mon Espace (<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>)</a>
                    <a href="<?= $basePath ?>/logout" class="btn btn-primary" style="background: rgba(239, 68, 68, 0.8);">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $basePath ?>/login" class="btn btn-primary">Connexion</a>
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
            <div class="flash-message flash-<?= $_SESSION['flash']['type'] ?>" style="padding: 1rem; margin-bottom: 2rem; border-radius: 8px; border: 1px solid var(--glass-border); background: rgba(255,255,255,0.05);">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?> 
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?= date('Y') ?> Aura. Plateforme solidaire d'associations.</p>
        </div>
    </footer>

    <!-- Main Script -->
    <script src="<?= $basePath ?>/js/main.js"></script>
</body>
</html>
