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
            $userModel->delete($id);
            $this->setFlash('success', "Utilisateur supprimé.");
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
