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
            <a href="<?= $basePath ?>/" class="logo">Aura</a>
            <div class="nav-links">
                <!-- Navigation adaptative -->
                <?php if ($user_role === 'admin'): ?>
                    <a href="<?= $adminDashboard ?>">Espace Admin</a>
                <?php elseif ($active_space === 'citizen' || !$user_id): ?>
                    <a href="<?= $basePath ?>/">Accueil</a>
                    <a href="<?= $basePath ?>/annonces">Annonces</a>
                    <a href="<?= $basePath ?>/associations">Associations</a>
                <?php endif; ?>
                
                <?php if($user_id): ?>
                    <?php if ($user_role !== 'admin'): ?>
                    <!-- Sélecteur d'espace intégré -->
                    <div style="display: flex; gap: 0.2rem; background: rgba(255,255,255,0.05); padding: 0.2rem; border-radius: 8px;">
                        <?php if ($user_role !== 'admin'): ?>
                        <a href="<?= $basePath ?>/dashboard/switch?to=citizen" 
                           class="btn <?= $active_space === 'citizen' ? 'btn-primary' : 'btn-secondary' ?>" 
                           style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: none;">Citoyen</a>
                        <?php endif; ?>
                        
                        <?php if ($user_role === 'president_assoc'): ?>
                            <a href="<?= $basePath ?>/dashboard/switch?to=association" 
                               class="btn <?= $active_space === 'association' ? 'btn-primary' : 'btn-secondary' ?>" 
                               style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: none;">Association</a>
                        <?php endif; ?>

                        <?php if ($user_role === 'president_siege'): ?>
                            <a href="<?= $basePath ?>/dashboard/switch?to=siege" 
                               class="btn <?= $active_space === 'siege' ? 'btn-primary' : 'btn-secondary' ?>" 
                               style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border: none;">Siège</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <a href="<?= $basePath ?>/logout" class="btn btn-primary" style="background: rgba(239, 68, 68, 0.8); box-shadow: none;">Déconnexion</a>
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
