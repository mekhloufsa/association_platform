<?php

class DashboardController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        // Admin goes directly to their own space, no citizen dashboard
        if ($_SESSION['user_role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        }

        // Space Switching Logic
        $current_space = $_SESSION['active_space'] ?? 'citizen';
        
        $role = $_SESSION['user_role'] ?? 'user';
        $spaces = ['citizen'];
        if ($role === 'president_assoc' || $role === 'admin') $spaces[] = 'association';
        if ($role === 'president_siege' || $role === 'admin') $spaces[] = 'siege';

        if ($current_space === 'association' && in_array('association', $spaces)) {
            $this->redirect('/assoc/dashboard');
            return;
        }
        if ($current_space === 'siege' && in_array('siege', $spaces)) {
            $this->redirect('/siege/dashboard');
            return;
        }

        $helpRequestModel = new HelpRequest();
        $requests = $helpRequestModel->findByUserId($_SESSION['user_id']);

        $donationModel = new Donation();
        $donations = $donationModel->findByUserId($_SESSION['user_id']);
        $totalDonated = $donationModel->getTotalDonated($_SESSION['user_id']);

        $volunteerModel = new Volunteer();
        $volunteering = $volunteerModel->findByUserId($_SESSION['user_id']);

        $assocRequestModel = new AssociationRequest();
        $assocRequests = $assocRequestModel->findByUserId($_SESSION['user_id']);

        $siegeRequestModel = new SiegeRequest();
        $siegeRequests = $siegeRequestModel->findByUserId($_SESSION['user_id']);

        $materialModel = new MaterialDonation();
        $materialDonations = $materialModel->findByUserId($_SESSION['user_id']);

        $this->render('dashboard/index', [
            'name' => $_SESSION['user_name'],
            'role' => $role,
            'requests' => $requests,
            'donations' => $donations,
            'materialDonations' => $materialDonations,
            'totalDonated' => $totalDonated,
            'volunteering' => $volunteering,
            'assocRequests' => $assocRequests,
            'siegeRequests' => $siegeRequests,
            'spaces' => $spaces,
            'current_space' => $current_space
        ]);
    }

    public function switchSpace() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $target = $_GET['to'] ?? 'citizen';
        $_SESSION['active_space'] = $target;
        
        if ($target === 'association') $this->redirect('/assoc/dashboard');
        elseif ($target === 'siege') $this->redirect('/siege/dashboard');
        else $this->redirect('/dashboard');
    }

    public function helpRequestForm() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if ($_SESSION['user_role'] === 'admin') {
            $this->setFlash('error', "Les administrateurs ne peuvent pas soumettre de demandes d'aide.");
            $this->redirect('/admin/dashboard');
        }

        $role = $_SESSION['user_role'] ?? 'user';
        $excludedAssocId = null;

        if ($role === 'president_assoc') {
            $assocModel = new Association();
            $assoc = $assocModel->findByPresidentId($_SESSION['user_id']);
            if ($assoc) $excludedAssocId = $assoc['id'];
        } elseif ($role === 'president_siege') {
            $siegeModel = new Siege();
            $siege = $siegeModel->findByManagerId($_SESSION['user_id']);
            if ($siege) $excludedAssocId = $siege['association_id'];
        }

        $assocModel = new Association();
        $associations = $assocModel->findApproved();
        
        $siegeModel = new Siege();
        $filteredAssocs = [];
        foreach ($associations as $assoc) {
            if ($excludedAssocId && $assoc['id'] == $excludedAssocId) continue;

            $sieges = $siegeModel->findAllPubliclyVisible($assoc['id']);
            if (!empty($sieges)) {
                $assoc['sieges'] = $sieges;
                $filteredAssocs[] = $assoc;
            }
        }

        $this->render('dashboard/help_request_form', [
            'associations' => $filteredAssocs
        ]);
    }

    public function submitHelpRequest() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
        }
        if ($_SESSION['user_role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        }

        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $siege_id = filter_input(INPUT_POST, 'siege_id', FILTER_VALIDATE_INT);

        // Fetch association_id from siege
        $siegeModel = new Siege();
        $siege = $siegeModel->findById($siege_id);
        $association_id = $siege ? $siege['association_id'] : null;

        $attachments = [];
        if (!empty($_FILES['files']['name'][0])) {
            $uploadDir = '../public/uploads/help_requests/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                $fileName = time() . '_' . basename($_FILES['files']['name'][$key]);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $targetPath)) {
                    $attachments[] = 'public/uploads/help_requests/' . $fileName;
                }
            }
        }

        $helpRequestModel = new HelpRequest();
        $result = $helpRequestModel->create([
            'user_id' => $_SESSION['user_id'],
            'association_id' => $association_id,
            'siege_id' => $siege_id,
            'subject' => $subject,
            'description' => $description,
            'attachments' => !empty($attachments) ? json_encode($attachments) : null
        ]);

        if ($result) {
            $this->setFlash('success', "Votre demande d'aide a été enregistrée avec succès.");
            $this->redirect('/dashboard');
        } else {
            $this->setFlash('error', "Une erreur est survenue lors de l'enregistrement.");
            $this->redirect('/dashboard/help-request');
        }
    }

    public function helpRequestDetail($id) {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $model = new HelpRequest();
        $request = $model->findByIdWithDetails($id);
        
        if (!$request || $request['user_id'] !== $_SESSION['user_id']) {
            $this->setFlash('error', "Demande introuvable ou accès non autorisé.");
            $this->redirect('/dashboard');
        }
        
        $this->render('dashboard/help_request_detail', ['request' => $request]);
    }

    public function donationForm() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if ($_SESSION['user_role'] === 'admin') {
            $this->setFlash('error', "Les administrateurs ne peuvent pas effectuer de dons en tant que citoyens.");
            $this->redirect('/admin/dashboard');
        }

        $search = $_GET['search'] ?? '';

        $assocModel = new Association();
        $associations = $assocModel->findApproved($search);
        
        $siegeModel = new Siege();
        foreach ($associations as &$assoc) {
            $assoc['sieges'] = $siegeModel->findAllPubliclyVisible($assoc['id']);
        }

        $this->render('dashboard/donation_form', [
            'associations' => $associations,
            'search' => $search
        ]);
    }

    public function submitDonation() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
        }
        if ($_SESSION['user_role'] === 'admin') {
            $this->redirect('/admin/dashboard');
        }

        $association_id = filter_input(INPUT_POST, 'association_id', FILTER_VALIDATE_INT);
        $siege_id = filter_input(INPUT_POST, 'siege_id', FILTER_VALIDATE_INT);
        $donation_type = filter_input(INPUT_POST, 'donation_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // financial or material

        if ($donation_type === 'material') {
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $materialModel = new MaterialDonation();
            $result = $materialModel->create([
                'user_id' => $_SESSION['user_id'],
                'association_id' => $association_id,
                'siege_id' => $siege_id,
                'category' => $category,
                'description' => $description,
                'quantity' => $quantity
            ]);
        } else {
            $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
            if ($amount <= 0) die("Montant invalide.");

            $donationModel = new Donation();
            $result = $donationModel->create([
                'user_id' => $_SESSION['user_id'],
                'association_id' => $association_id ? $association_id : null,
                'siege_id' => $siege_id ? $siege_id : null,
                'amount' => $amount,
                'type' => 'onetime',
                'message' => ''
            ]);
        }

        if ($result) {
            $_SESSION['last_donation_assoc_id'] = $association_id;
            $this->redirect('/dashboard/thank-you');
        } else {
            $this->setFlash('error', "Une erreur est survenue lors du traitement.");
            $this->redirect('/dashboard/donation');
        }
    }

    public function materialDonationDetail($id) {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');
        
        $model = new MaterialDonation();
        $donation = $model->findByIdWithDetails($id);
        
        if (!$donation || $donation['user_id'] !== $_SESSION['user_id']) {
            $this->setFlash('error', "Don introuvable ou accès non autorisé.");
            $this->redirect('/dashboard');
        }
        
        $this->render('dashboard/material_donation_detail', ['donation' => $donation]);
    }

    public function campaigns() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $campaignModel = new Campaign();
        $campaigns = $campaignModel->findOpen();

        $this->render('dashboard/campaigns', [
            'campaigns' => $campaigns
        ]);
    }

    public function registerVolunteer() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/dashboard');
        }

        $campaign_id = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);

        if (!$campaign_id) {
            die("Campagne invalide.");
        }

        $volunteerModel = new Volunteer();
        $result = $volunteerModel->register($_SESSION['user_id'], $campaign_id);

        if ($result) {
            $this->setFlash('success', "Vous êtes maintenant inscrit en tant que bénévole !");
            $this->redirect('/dashboard');
        } else {
            $this->setFlash('error', "Erreur lors de l'inscription au bénévolat.");
            $this->redirect('/dashboard/campaigns');
        }
    }

    public function thankYou() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $assocId = $_SESSION['last_donation_assoc_id'] ?? null;
        $message = "Merci pour votre don généreux ! Votre contribution nous aide à poursuivre nos missions humanitaires.";

        if ($assocId) {
            $assocModel = new Association();
            $assoc = $assocModel->findById($assocId);
            if ($assoc && !empty($assoc['thank_you_message'])) {
                $message = $assoc['thank_you_message'];
            }
            unset($_SESSION['last_donation_assoc_id']);
        }

        $this->render('dashboard/thank_you', [
            'message' => $message
        ]);
    }
}
