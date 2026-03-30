<?php

class AdminController extends Controller {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $userModel = new User();
        $usersCount = count($userModel->findAll());

        $assocModel = new Association();
        $pendingAssocs = $assocModel->findPending();
        $totalAssocs = count($assocModel->findAll());

        $donationModel = new Donation();
        $totalDonations = Database::getInstance()->query("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'")->fetch()['total'] ?? 0;

        $helpRequestModel = new HelpRequest();
        $pendingRequestsCount = count(Database::getInstance()->query("SELECT id FROM help_requests WHERE status = 'pending'")->fetchAll());

        $this->render('admin/dashboard', [
            'name' => $_SESSION['user_name'],
            'usersCount' => $usersCount,
            'totalAssocs' => $totalAssocs,
            'pendingAssocs' => $pendingAssocs,
            'totalDonations' => $totalDonations,
            'pendingRequestsCount' => $pendingRequestsCount
        ]);
    }

    public function validateAssociation() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $assoc_id = filter_input(INPUT_POST, 'assoc_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($assoc_id && in_array($status, ['approved', 'rejected'])) {
            $assocModel = new Association();
            $assocModel->updateStatus($assoc_id, $status);
            $this->setFlash('success', "Statut de l'association mis à jour.");
        }

        $this->redirect('/admin/dashboard');
    }

    public function users() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';

        $userModel = new User();
        $users = $userModel->findAll($search, $role, $status);

        $this->render('admin/users', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status
        ]);
    }

    public function deleteUser() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $userModel = new User();
            $targetUser = $userModel->findById($id);
            
            if ($targetUser && $targetUser['role'] === 'admin') {
                $this->setFlash('error', "Impossible de supprimer un compte administrateur.");
            } else {
                $userModel->delete($id);
                $this->setFlash('success', "Utilisateur supprimé.");
            }
        }
        $this->redirect('/admin/users');
    }

    public function associations() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $assocModel = new Association();
        $associations = $assocModel->findAll($search, $status);

        $this->render('admin/associations', [
            'associations' => $associations,
            'search' => $search,
            'status' => $status
        ]);
    }

    public function deleteAssociation() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $assocModel = new Association();
            $assocModel->delete($id);
            $this->setFlash('success', "Association supprimée.");
        }
        $this->redirect('/admin/associations');
    }

    public function helpRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $helpRequestModel = new HelpRequest();
        $requests = $helpRequestModel->findAllExtended();

        $this->render('admin/help_requests', [
            'requests' => $requests
        ]);
    }

    public function donations() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $donationModel = new Donation();
        $donations = $donationModel->findAllWithDetails();
        $this->render('admin/donations', ['donations' => $donations]);
    }

    public function materialDonations() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $mdModel = new MaterialDonation();
        $donations = $mdModel->findAllWithDetails();
        $this->render('admin/material_donations', ['donations' => $donations]);
    }

    public function campaigns() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findAllWithDetails();
        $this->render('admin/campaigns', ['campaigns' => $campaigns]);
    }

    public function updateCampaignStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($id && in_array($status, ['open', 'closed', 'finished'])) {
            $campaignModel = new Campaign();
            $campaignModel->updateStatus($id, $status);
            $this->setFlash('success', "Statut de la campagne mis à jour.");
        }
        $this->redirect('/admin/campaigns');
    }

    public function deleteCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $campaignModel = new Campaign();
            $campaignModel->delete($id);
            $this->setFlash('success', "Campagne supprimée.");
        }
        $this->redirect('/admin/campaigns');
    }

    public function editCampaign($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $campaignModel = new Campaign();
        $campaign = $campaignModel->findById($id);
        
        if (!$campaign) {
            $this->setFlash('error', "Campagne introuvable.");
            $this->redirect('/admin/campaigns');
        }

        $this->render('admin/campaign_form', ['campaign' => $campaign]);
    }

    public function saveCampaign() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $max_volunteers = filter_input(INPUT_POST, 'max_volunteers', FILTER_VALIDATE_INT);
        $campaign_type = filter_input(INPUT_POST, 'campaign_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $need_type = filter_input(INPUT_POST, 'need_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $financial_goal = filter_input(INPUT_POST, 'financial_goal', FILTER_VALIDATE_FLOAT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'title' => $title,
            'description' => $description,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location' => $location,
            'max_volunteers' => $max_volunteers ?: null,
            'campaign_type' => $campaign_type,
            'need_type' => $need_type,
            'financial_goal' => $need_type === 'financial' ? $financial_goal : null,
            'status' => $status
        ];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/campaigns/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $data['image_path'] = 'public/uploads/campaigns/' . $fileName;
            }
        }

        $campaignModel = new Campaign();
        if ($id) {
            $result = $campaignModel->update($id, $data);
            if ($result) {
                $this->setFlash('success', "Campagne modifiée avec succès.");
            } else {
                $this->setFlash('error', "Erreur lors de la modification.");
            }
        }

        $this->redirect('/admin/campaigns');
    }

    public function helpRequestDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') $this->redirect('/login');
        $model = new HelpRequest();
        $request = $model->findByIdWithDetails($id);
        if (!$request) { $this->setFlash('error', "Demande introuvable."); $this->redirect('/admin/help-requests'); }
        $this->render('admin/help_request_detail', ['request' => $request]);
    }

    public function donationDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') $this->redirect('/login');
        $model = new Donation();
        $donation = $model->findByIdWithDetails($id);
        if (!$donation) { $this->setFlash('error', "Don introuvable."); $this->redirect('/admin/donations'); }
        $this->render('admin/donation_detail', ['donation' => $donation]);
    }

    public function materialDonationDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') $this->redirect('/login');
        $model = new MaterialDonation();
        $donation = $model->findByIdWithDetails($id);
        if (!$donation) { $this->setFlash('error', "Don introuvable."); $this->redirect('/admin/material-donations'); }
        $this->render('admin/material_donation_detail', ['donation' => $donation]);
    }

    public function campaignDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') $this->redirect('/login');
        $model = new Campaign();
        $campaign = $model->findById($id);
        if (!$campaign) { $this->setFlash('error', "Campagne introuvable."); $this->redirect('/admin/campaigns'); }
        
        $volModel = new Volunteer();
        $volunteers = $volModel->findByCampaignId($id);
        
        $this->render('admin/campaign_detail', ['campaign' => $campaign, 'volunteers' => $volunteers]);
    }

    public function annonces() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $annonceModel = new Annonce();
        $annonces = $annonceModel->findAll();
        $this->render('admin/annonces', ['annonces' => $annonces]);
    }

    public function addAnnonce() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $this->render('admin/annonce_form');
    }

    public function editAnnonce($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        $annonceModel = new Annonce();
        $annonce = $annonceModel->findById($id);
        
        if (!$annonce) {
            $this->setFlash('error', "Annonce introuvable.");
            $this->redirect('/admin/annonces');
        }

        $this->render('admin/annonce_form', ['annonce' => $annonce]);
    }

    public function saveAnnonce() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $visibility = filter_input(INPUT_POST, 'visibility', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $image_path = false; // false means no new file uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/annonces/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $image_path = $uploadDir . $fileName;
            }
        }

        $attachment_path = false;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/attachments/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $fileName = time() . '_' . basename($_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $fileName)) {
                $attachment_path = $uploadDir . $fileName;
            }
        }

        $annonceModel = new Annonce();
        
        $data = [
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'visibility' => $visibility,
            'image_path' => $image_path !== false ? $image_path : null, // for create only, null if no image
            'attachment_path' => $attachment_path !== false ? $attachment_path : null // for create only
        ];

        if ($id) {
            // Unset paths if not updated
            if ($image_path === false) unset($data['image_path']);
            if ($attachment_path === false) unset($data['attachment_path']);
            
            $result = $annonceModel->update($id, $data);
            if ($result) {
                $this->setFlash('success', "Annonce modifiée avec succès.");
            } else {
                $this->setFlash('error', "Erreur lors de la modification de l'annonce.");
            }
        } else {
            $data['author_user_id'] = $_SESSION['user_id'];
            $result = $annonceModel->create($data);
            if ($result) {
                $this->setFlash('success', "Annonce créée avec succès.");
            } else {
                $this->setFlash('error', "Erreur lors de la création de l'annonce.");
            }
        }

        $this->redirect('/admin/annonces');
    }

    public function deleteAnnonce() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $annonceModel = new Annonce();
        
        $annonce = $annonceModel->findById($id);
        if ($annonce) {
            if ($annonce['image_path'] && file_exists($annonce['image_path'])) unlink($annonce['image_path']);
            if ($annonce['attachment_path'] && file_exists($annonce['attachment_path'])) unlink($annonce['attachment_path']);
            $annonceModel->delete($id);
            $this->setFlash('success', "Annonce supprimée.");
        }
        $this->redirect('/admin/annonces');
    }

    public function deleteSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $siegeModel = new Siege();
            $siegeModel->delete($id);
            $this->setFlash('success', "Siège supprimé.");
        }
        $this->redirect('/admin/dashboard');
    }

    public function userDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->setFlash('error', "Utilisateur non trouvé.");
            $this->redirect('/admin/users');
        }

        $this->render('admin/user_detail', ['user' => $user]);
    }

    public function associationDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $association = $assocModel->findById($id);

        if (!$association) {
            $this->setFlash('error', "Association non trouvée.");
            $this->redirect('/admin/associations');
        }

        $siegeModel = new Siege();
        $sieges = $siegeModel->findByAssociationId($id);

        $this->render('admin/assoc_detail', [
            'association' => $association,
            'sieges' => $sieges
        ]);
    }

    public function siegeDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $siegeModel = new Siege();
        $siege = $siegeModel->findById($id);

        if (!$siege) {
            $this->setFlash('error', "Siège non trouvé.");
            $this->redirect('/admin/dashboard');
        }

        $this->render('admin/siege_detail', ['siege' => $siege]);
    }
}
