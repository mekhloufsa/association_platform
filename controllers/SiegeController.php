<?php

class SiegeController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        $donations = [];
        $materialDonations = [];
        
        if ($siege) {
            $donationModel = new Donation();
            $donations = $donationModel->findBySiegeId($siege['id']);
            
            $mdModel = new MaterialDonation();
            $materialDonations = $mdModel->findBySiegeId($siege['id']);
        }

        $this->render('siege/dashboard', [
            'name' => $_SESSION['user_name'],
            'siege' => $siege,
            'donations' => $donations,
            'materials' => $materialDonations
        ]);
    }

    private function getSiegeInfo() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT s.*, a.name as association_name, w.name as wilaya_name 
                             FROM sieges s 
                             JOIN associations a ON s.association_id = a.id 
                             JOIN wilayas w ON s.wilaya_id = w.id
                             WHERE s.manager_user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    public function helpRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        if (!$siege) {
             $this->render('siege/dashboard', [
                'error' => "Vous n'êtes assigné à aucun siège. Veuillez contacter l'administrateur de votre association.",
                'name' => $_SESSION['user_name']
            ]);
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT hr.*, u.nom, u.prenom, u.email 
                              FROM help_requests hr 
                              JOIN users u ON hr.user_id = u.id 
                              WHERE hr.siege_id = ? 
                              ORDER BY hr.created_at DESC");
        $stmt->execute([$siege['id']]);
        $requests = $stmt->fetchAll();

        $this->render('siege/help_requests', [
            'requests' => $requests,
            'siege' => $siege
        ]);
    }

    public function updateHelpRequestStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/siege/dashboard');
        }

        $request_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $appointment_date = filter_input(INPUT_POST, 'appointment_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $appointment_time = filter_input(INPUT_POST, 'appointment_time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $refusal_message = filter_input(INPUT_POST, 'refusal_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($request_id && in_array($status, ['accepted', 'rejected'])) {
            $appointment_details = null;
            if ($status === 'accepted') {
                $appointment_details = "Rendez-vous le " . ($appointment_date ?: 'TBD') . " à " . ($appointment_time ?: 'TBD');
            }

            if ($status === 'rejected' && empty($refusal_message)) {
                $this->setFlash('error', "Le message de refus est obligatoire.");
                $this->redirect('/siege/help-requests');
                return;
            }

            $helpRequestModel = new HelpRequest();
            $helpRequestModel->updateStatus($request_id, $status, $appointment_details, $refusal_message);
            $this->setFlash('success', "Le statut de la demande a été mis à jour.");
        }

        $this->redirect('/siege/help-requests');
    }

    public function donations() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        if (!$siege) {
            $this->redirect('/siege/dashboard');
        }

        $mdModel = new MaterialDonation();
        $donations = $mdModel->findBySiegeId($siege['id']);

        $this->render('siege/donations', [
            'donations' => $donations,
            'siege' => $siege
        ]);
    }

    public function updateDonationStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/siege/dashboard');
        }

        $donation_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pickup_date = filter_input(INPUT_POST, 'pickup_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $manager_message = filter_input(INPUT_POST, 'manager_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($donation_id && in_array($status, ['scheduled', 'collected', 'cancelled'])) {
            if ($status === 'scheduled' && empty($pickup_date)) {
                $this->setFlash('error', "La date de rendez-vous est obligatoire.");
                $this->redirect('/siege/donations');
                return;
            }
            if ($status === 'cancelled' && empty($manager_message)) {
                $this->setFlash('error', "Le message de refus est obligatoire.");
                $this->redirect('/siege/donations');
                return;
            }

            $mdModel = new MaterialDonation();
            $mdModel->updateStatus($donation_id, $status, $pickup_date ?: null, $manager_message ?: null);
            $this->setFlash('success', "Le statut du don a été mis à jour.");
        }

        $this->redirect('/siege/donations');
    }

    public function materialDonationDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        $mdModel = new MaterialDonation();
        $donation = $mdModel->findByIdWithDetails($id);

        if (!$donation || $donation['siege_id'] != $siege['id']) {
            $this->setFlash('error', "Don introuvable ou accès non autorisé.");
            $this->redirect('/siege/donations');
        }

        $this->render('siege/material_donation_detail', [
            'donation' => $donation,
            'siege' => $siege
        ]);
    }

    public function volunteers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        
        // Find volunteers in this wilaya for this association's campaigns
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT v.*, u.nom, u.prenom, c.title as campaign_title 
                             FROM volunteers v 
                             JOIN users u ON v.user_id = u.id 
                             JOIN campaigns c ON v.campaign_id = c.id 
                             WHERE u.wilaya_id = ? AND c.association_id = ?");
        $stmt->execute([$siege['wilaya_id'], $siege['association_id']]);
        $volunteers = $stmt->fetchAll();

        $this->render('siege/volunteers', [
            'volunteers' => $volunteers,
            'siege' => $siege
        ]);
    }

    public function campaigns() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        if (!$siege) $this->redirect('/siege/dashboard');

        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findBySiegeId($siege['id']);

        $this->render('siege/campaigns', [
            'campaigns' => $campaigns,
            'siege' => $siege
        ]);
    }

    public function addCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege') {
            $this->redirect('/login');
        }

        $siege = $this->getSiegeInfo();
        if (!$siege) $this->redirect('/siege/dashboard');

        $this->render('siege/add_campaign', ['siege' => $siege]);
    }

    public function saveCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_siege' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/siege/dashboard');
        }

        $siege = $this->getSiegeInfo();
        if (!$siege) $this->redirect('/siege/dashboard');

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $target = filter_input(INPUT_POST, 'target', FILTER_VALIDATE_INT);
        $need_type = filter_input(INPUT_POST, 'need_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Upload image
        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/association_platform/public/uploads/campaigns/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image_path = 'uploads/campaigns/' . $fileName;
            }
        }

        $data = [
            'association_id' => $siege['association_id'],
            'siege_id' => $siege['id'],
            'title' => $title,
            'description' => $description,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location' => $location,
            'need_type' => $need_type,
            'max_volunteers' => ($need_type === 'personnel') ? $target : null,
            'financial_goal' => ($need_type === 'financial') ? $target : null,
            'campaign_type' => 'local',
            'image_path' => $image_path,
            'approval_status' => 'pending' // En attente de validation par le président national
        ];

        $campaignModel = new Campaign();
        if ($campaignModel->create($data)) {
            $this->setFlash('success', "Campagne locale proposée avec succès. Elle est en attente de validation par l'association.");
            $this->redirect('/siege/campaigns');
        } else {
            $this->setFlash('error', "Erreur lors de la proposition de la campagne.");
            $this->redirect('/siege/add-campaign');
        }
    }
}
