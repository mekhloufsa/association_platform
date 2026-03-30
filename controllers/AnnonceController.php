<?php

class AnnonceController extends Controller {
    public function index() {
        $isLoggedIn = isset($_SESSION['user_id']);
        $userId = $_SESSION['user_id'] ?? null;
        
        $annonceModel = new Annonce();
        $baseAnnonces = $annonceModel->findVisible($isLoggedIn);
        
        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findOpen(100);

        $volModel = new Volunteer();

        $flux = [];
        foreach($baseAnnonces as $a) {
            $a['item_type'] = 'annonce';
            $a['sort_date'] = strtotime($a['created_at']);
            $a['display_date'] = $a['published_at'] ?: $a['created_at'];
            $flux[] = $a;
        }

        foreach($campaigns as $c) {
            $c['item_type'] = 'campaign';
            $c['sort_date'] = strtotime($c['created_at']);
            $c['display_date'] = $c['created_at'];
            
            // Map common fields to make view easier
            $c['content'] = $c['description'];
            $c['prenom'] = $c['association_name'];
            $c['nom'] = '';
            $c['visibility'] = 'public';

            // Check participation
            $c['already_participated'] = false;
            if ($isLoggedIn) {
                $c['already_participated'] = $volModel->isRegistered($userId, $c['id']);
            }

            $flux[] = $c;
        }

        usort($flux, function($a, $b) {
            return $b['sort_date'] <=> $a['sort_date'];
        });

        $this->render('annonces/index', [
            'annonces' => $flux
        ]);
    }

    public function show($id) {
        $model = new Annonce();
        $annonce = $model->findById($id);

        if (!$annonce) {
            $this->redirect('/annonces');
        }

        if ($annonce['visibility'] === 'users_only' && !isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $this->render('annonces/show', [
            'annonce' => $annonce
        ]);
    }

    public function campaignShow($id) {
        $model = new Campaign();
        $campaign = $model->findById($id);

        if (!$campaign) {
            $this->setFlash('error', "Campagne introuvable.");
            $this->redirect('/annonces');
        }

        $alreadyParticipated = false;
        if (isset($_SESSION['user_id'])) {
            $volModel = new Volunteer();
            $alreadyParticipated = $volModel->isRegistered($_SESSION['user_id'], $id);
        }

        $this->render('annonces/campaign_show', [
            'campaign' => $campaign,
            'alreadyParticipated' => $alreadyParticipated
        ]);
    }

    public function registerToCampaign($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->setFlash('error', "Vous devez être connecté pour participer.");
            $this->redirect('/login');
        }

        $volModel = new Volunteer();
        $result = $volModel->register($_SESSION['user_id'], $id);

        if ($result) {
            $this->setFlash('success', "Votre participation a été enregistrée avec succès !");
        } else {
            $this->setFlash('error', "Une erreur est survenue lors de l'enregistrement.");
        }

        $this->redirect('/campaign/' . $id);
    }
}
