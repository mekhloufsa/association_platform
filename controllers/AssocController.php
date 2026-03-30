<?php

class AssocController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        if (!$association) {
            die("Association non trouvée pour cet utilisateur.");
        }

        $siegeModel = new Siege();
        $sieges = $siegeModel->findByAssociationId($association['id']);

        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findByAssociationId($association['id']);

        $donationModel = new Donation();
        $donations = $donationModel->findByAssociationId($association['id']);

        $materialModel = new MaterialDonation();
        $materials = $materialModel->findByAssociationId($association['id']);

        $this->render('assoc/dashboard', [
            'name' => $_SESSION['user_name'],
            'association' => $association,
            'siegesCount' => count($sieges),
            'campaignsCount' => count($campaigns),
            'donations' => $donations,
            'materials' => $materials
        ]);
    }

    public function sieges() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $siegeModel = new Siege();
        $sieges = $siegeModel->findByAssociationId($association['id']);

        $this->render('assoc/sieges', [
            'sieges' => $sieges
        ]);
    }

    public function siegeDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $siegeModel = new Siege();
        $siege = $siegeModel->findById($id);

        // Security: ensure siege belongs to this president's association
        if (!$siege || $siege['association_id'] != $association['id']) {
            $this->setFlash('error', "Siège non trouvé ou accès non autorisé.");
            $this->redirect('/assoc/sieges');
        }

        $db = Database::getInstance();

        // Financial donations for this siege
        $stmt = $db->prepare("SELECT d.*, u.nom, u.prenom, u.email 
                              FROM donations d 
                              JOIN users u ON d.user_id = u.id 
                              WHERE d.siege_id = ? 
                              ORDER BY d.created_at DESC");
        $stmt->execute([$id]);
        $donations = $stmt->fetchAll();

        // Material donations for this siege
        $stmt = $db->prepare("SELECT md.*, u.nom, u.prenom, u.email 
                              FROM material_donations md 
                              JOIN users u ON md.user_id = u.id 
                              WHERE md.siege_id = ? 
                              ORDER BY md.created_at DESC");
        $stmt->execute([$id]);
        $materialDonations = $stmt->fetchAll();

        // Help requests for this siege
        $stmt = $db->prepare("SELECT hr.*, u.nom, u.prenom, u.email 
                              FROM help_requests hr 
                              JOIN users u ON hr.user_id = u.id 
                              WHERE hr.siege_id = ? 
                              ORDER BY hr.created_at DESC");
        $stmt->execute([$id]);
        $helpRequests = $stmt->fetchAll();

        $this->render('assoc/siege_detail', [
            'siege'           => $siege,
            'association'     => $association,
            'donations'       => $donations,
            'materialDonations' => $materialDonations,
            'helpRequests'    => $helpRequests,
        ]);
    }

    public function addSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $wilayaModel = new Wilaya();
        $wilayas = $wilayaModel->findAll();

        $this->render('assoc/siege_form', [
            'wilayas' => $wilayas
        ]);
    }

    public function saveSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $wilaya_id = filter_input(INPUT_POST, 'wilaya_id', FILTER_VALIDATE_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $siegeModel = new Siege();
        
        // Check if a siege already exists in this wilaya for this association
        $existing = $siegeModel->findByAssocAndWilaya($association['id'], $wilaya_id);
        if ($existing) {
            $this->setFlash('error', "Un siège existe déjà pour cette wilaya.");
            $this->redirect('/assoc/add-siege');
        }

        $result = $siegeModel->create([
            'association_id' => $association['id'],
            'wilaya_id' => $wilaya_id,
            'address' => $address
        ]);

        if ($result) {
            $this->setFlash('success', "Siège ajouté avec succès.");
            $this->redirect('/assoc/sieges');
        } else {
            $this->setFlash('error', "Erreur lors de l'ajout du siège.");
            $this->redirect('/assoc/add-siege');
        }
    }

    public function campaigns() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $campaignModel = new Campaign();
        $allCampaigns = $campaignModel->findByAssociationId($association['id']);
        
        $pendingCampaigns = $campaignModel->findPendingByAssociation($association['id']);
        $activeCampaigns = [];
        foreach($allCampaigns as $c) {
            if($c['approval_status'] !== 'pending') {
                $activeCampaigns[] = $c;
            }
        }

        $this->render('assoc/campaigns', [
            'campaigns' => $activeCampaigns,
            'pendingCampaigns' => $pendingCampaigns
        ]);
    }

    public function addCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $this->render('assoc/campaign_form');
    }

    public function saveCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $max_volunteers = filter_input(INPUT_POST, 'max_volunteers', FILTER_VALIDATE_INT);
        $campaign_type = filter_input(INPUT_POST, 'campaign_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $need_type = filter_input(INPUT_POST, 'need_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $financial_goal = filter_input(INPUT_POST, 'financial_goal', FILTER_VALIDATE_FLOAT);

        // Upload image
        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = '../public/uploads/campaigns/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image_path = 'public/uploads/campaigns/' . $fileName;
            }
        }

        $campaignModel = new Campaign();
        $result = $campaignModel->create([
            'association_id' => $association['id'],
            'title' => $title,
            'description' => $description,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location' => $location,
            'max_volunteers' => $max_volunteers ?: null,
            'campaign_type' => $campaign_type,
            'need_type' => $need_type,
            'financial_goal' => $need_type === 'financial' ? $financial_goal : null,
            'image_path' => $image_path,
            'approval_status' => 'approved'
        ]);

        if ($result) {
            $this->setFlash('success', "Campagne créée avec succès !");
            $this->redirect('/assoc/campaigns');
        } else {
            $this->setFlash('error', "Erreur lors de la création de la campagne.");
            $this->redirect('/assoc/add-campaign');
        }
    }

    public function volunteers($campaignId) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $volunteerModel = new Volunteer();
        $volunteers = $volunteerModel->findByCampaignId($campaignId);

        $campaignModel = new Campaign();
        $campaign = $campaignModel->findById($campaignId);

        $this->render('assoc/volunteers', [
            'volunteers' => $volunteers,
            'campaign' => $campaign
        ]);
    }

    public function updateVolunteerStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
        }

        $volunteer_id = filter_input(INPUT_POST, 'volunteer_id', FILTER_VALIDATE_INT);
        $campaign_id = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($volunteer_id && $status) {
            $volunteerModel = new Volunteer();
            $volunteerModel->updateStatus($volunteer_id, $status);
            $this->setFlash('success', "Statut du bénévole mis à jour.");
        }

        $this->redirect('/assoc/campaign/' . $campaign_id);
    }

    public function updateCampaignApproval() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
        }

        $campaign_id = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($campaign_id && in_array($status, ['approved', 'rejected'])) {
            $campaignModel = new Campaign();
            $campaignModel->updateApprovalStatus($campaign_id, $status);
            $this->setFlash('success', "Le statut de la campagne locale a été mis à jour.");
        }

        $this->redirect('/assoc/campaigns');
    }

    public function helpRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $helpRequestModel = new HelpRequest();
        $requests = $helpRequestModel->findByAssociationId($association['id']);

        $this->render('assoc/help_requests', [
            'requests' => $requests
        ]);
    }

    public function updateHelpRequestStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
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
                $this->redirect('/assoc/help-requests');
                return;
            }

            $helpRequestModel = new HelpRequest();
            $helpRequestModel->updateStatus($request_id, $status, $appointment_details, $refusal_message);
            $this->setFlash('success', "Le statut de la demande a été mis à jour.");
        }

        $this->redirect('/assoc/help-requests');
    }

    public function editSiege($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $siegeModel = new Siege();
        $siege = $siegeModel->findById($id);

        $userModel = new User();
        $availableManagers = $userModel->findAll('', 'president_siege');

        $wilayaModel = new Wilaya();
        $wilayas = $wilayaModel->findAll();

        $this->render('assoc/siege_edit', [
            'siege' => $siege,
            'managers' => $availableManagers,
            'wilayas' => $wilayas
        ]);
    }

    public function updateSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/sieges');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $wilaya_id = filter_input(INPUT_POST, 'wilaya_id', FILTER_VALIDATE_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $manager_user_id = filter_input(INPUT_POST, 'manager_user_id', FILTER_VALIDATE_INT);

        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE sieges SET wilaya_id = ?, address = ?, manager_user_id = ? WHERE id = ?");
        $result = $stmt->execute([$wilaya_id, $address, $manager_user_id ?: null, $id]);

        if ($result) {
            $this->setFlash('success', "Siège mis à jour avec succès.");
        } else {
            $this->setFlash('error', "Erreur lors de la mise à jour du siège.");
        }

        $this->redirect('/assoc/sieges');
    }

    public function deleteSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/sieges');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $siegeModel = new Siege();
        $result = $siegeModel->delete($id);

        if ($result) {
            $this->setFlash('success', "Siège supprimé définitivement.");
        } else {
            $error = $siegeModel->errorInfo();
            $this->setFlash('error', "Erreur lors de la suppression du siège : " . ($error[2] ?? 'Erreur inconnue'));
        }

        $this->redirect('/assoc/sieges');
    }

    public function removeSiegeManager() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/sieges');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $siegeModel = new Siege();
        $siege = $siegeModel->findById($id);
        
        if ($siege && $siege['manager_user_id']) {
            $userModel = new User();
            $userModel->updateRole($siege['manager_user_id'], 'user');
        }

        $result = $siegeModel->updateManager($id, null);

        if ($result) {
            $this->setFlash('success', "Responsable retiré. Le compte utilisateur a été rétabli en rôle Citoyen.");
        } else {
            $this->setFlash('error', "Erreur lors du retrait du responsable.");
        }

        $this->redirect('/assoc/sieges');
    }

    public function settings() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $this->render('assoc/settings', [
            'association' => $association
        ]);
    }

    public function saveSettings() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/dashboard');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $thank_you_message = filter_input(INPUT_POST, 'thank_you_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $result = $assocModel->updateThankYouMessage($association['id'], $thank_you_message);

        if ($result) {
            $this->setFlash('success', "Paramètres mis à jour avec succès.");
        } else {
            $this->setFlash('error', "Erreur lors de la mise à jour des paramètres.");
        }

        $this->redirect('/assoc/dashboard');
    }

    public function materialDonationDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findByPresidentId($_SESSION['user_id']);

        $mdModel = new MaterialDonation();
        $donation = $mdModel->findByIdWithDetails($id);

        // Security check: donation belongs to this association
        if (!$donation || $donation['association_id'] != $association['id']) {
            $this->setFlash('error', "Don introuvable ou accès non autorisé.");
            $this->redirect('/assoc/dashboard');
        }

        $this->render('siege/material_donation_detail', [
            'donation' => $donation
        ]);
    }
}
