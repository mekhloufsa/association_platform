<?php

class HomeController extends Controller {
    
    public function index() {
        require_once '../models/Annonce.php';
        
        $db = Database::getInstance();
        
        // Vrais statistiques (ou 0 si null)
        $stats = [
            'associations' => $db->query("SELECT COUNT(*) FROM associations WHERE national_account_status = 'approved'")->fetchColumn() ?: 0,
            'fonds' => $db->query("SELECT SUM(amount) FROM donations WHERE status = 'completed'")->fetchColumn() ?: 0,
            'benevoles' => $db->query("SELECT COUNT(DISTINCT user_id) FROM volunteers WHERE status IN ('registered', 'confirmed', 'attended')")->fetchColumn() ?: 0,
            'campagnes' => $db->query("SELECT COUNT(*) FROM campaigns WHERE status = 'finished'")->fetchColumn() ?: 0
        ];
        
        // Récupérer les 3 dernières annonces publiées
        $annonceModel = new Annonce();
        $recentAnnonces = $annonceModel->findPublished(3);

        $title = "Plateforme Solidaire des Associations";
        $description = "Un espace centralisé pour soutenir les initiatives locales, faciliter les dons et promouvoir le bénévolat au service de l'intérêt général.";

        $this->render('home/index', [
            'title' => $title,
            'description' => $description,
            'stats' => $stats,
            'recentAnnonces' => $recentAnnonces
        ]);
    }

}
