<?php

class RequestController extends Controller {
    
    // --- Association Workflow ---

    public function createAssociation() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $national_id = filter_input(INPUT_POST, 'national_id_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // File upload logic (simplified for now)
            $logo_path = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/association_platform/public/uploads/logos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $logo_path = 'uploads/logos/' . time() . '_' . basename($_FILES['logo']['name']);
                move_uploaded_file($_FILES['logo']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/association_platform/public/' . $logo_path);
            }

            $model = new AssociationRequest();
            $result = $model->create([
                'user_id' => $_SESSION['user_id'],
                'name' => $name,
                'description' => $description,
                'national_id_number' => $national_id,
                'logo_path' => $logo_path
            ]);

            if ($result) {
                $this->setFlash('success', 'Votre demande de création d\'association a été soumise.');
                $this->redirect('/dashboard');
            }
        }

        $this->render('requests/assoc_create');
    }

    public function adminRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $model = new AssociationRequest();
        $requests = $model->findAll();

        $this->render('admin/association_requests', ['requests' => $requests]);
    }

    public function assocRequestDetail($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $model = new AssociationRequest();
        $request = $model->findByIdWithDetails($id);

        if (!$request) {
            $this->setFlash('error', "Demande introuvable.");
            $this->redirect('/admin/requests');
        }

        $this->render('admin/association_request_detail', ['request' => $request]);
    }

    // --- Siege Workflow ---

    public function applyForSiege() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $wilaya_id = $_GET['wilaya_id'] ?? null;
        $siegeModel = new Siege();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $siege_id = filter_input(INPUT_POST, 'siege_id', FILTER_VALIDATE_INT);
            $national_id = filter_input(INPUT_POST, 'national_id_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contact = filter_input(INPUT_POST, 'contact_info', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $model = new SiegeRequest();
            $result = $model->create([
                'user_id' => $_SESSION['user_id'],
                'siege_id' => $siege_id,
                'national_id_number' => $national_id,
                'description' => $description,
                'contact_info' => $contact
            ]);

            if ($result) {
                $this->setFlash('success', 'Votre candidature pour le siège a été soumise.');
                $this->redirect('/dashboard');
            }
        }

        // Get vacant sieges
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        $vacantSieges = $siegeModel->findVacantByWilaya($wilaya_id ?: $user['wilaya_id']);

        $wilayaModel = new Wilaya();
        $wilayas = $wilayaModel->findAll();

        $this->render('requests/siege_apply', [
            'sieges' => $vacantSieges,
            'wilayas' => $wilayas,
            'current_wilaya' => $wilaya_id ?: $user['wilaya_id']
        ]);
    }

    public function assocSiegeRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc') {
            $this->redirect('/login');
        }

        $assocModel = new Association();
        $assoc = $assocModel->findByPresidentId($_SESSION['user_id']);
        
        $model = new SiegeRequest();
        $requests = $model->findByAssociationId($assoc['id']);

        $this->render('assoc/siege_requests', ['requests' => $requests]);
    }

    public function reviewAssociation() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/requests');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $action = $_POST['action']; // approve or reject

        $model = new AssociationRequest();
        $request = null;
        // Find request by ID (need to add findById to AssociationRequest model)
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM association_requests WHERE id = ?");
        $stmt->execute([$id]);
        $request = $stmt->fetch();

        if ($request) {
            if ($action === 'approve') {
                $model->updateStatus($id, 'approved');
                
                // Create the association
                $assocModel = new Association();
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request['name'])));
                $assocModel->create([
                    'name' => $request['name'],
                    'slug' => $slug,
                    'description' => $request['description'],
                    'president_user_id' => $request['user_id']
                ]);

                // Update user role
                $db->prepare("UPDATE users SET role = 'president_assoc' WHERE id = ?")->execute([$request['user_id']]);
                
                $this->setFlash('success', 'Association créée avec succès.');
            } else {
                $model->updateStatus($id, 'rejected');
                $this->setFlash('info', 'Demande rejetée.');
            }
        }

        $this->redirect('/admin/requests');
    }

    public function reviewSiege() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'president_assoc' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/assoc/siege-requests');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $action = $_POST['action'];

        $model = new SiegeRequest();
        $request = $model->findById($id);

        if ($request) {
            if ($action === 'approve') {
                $model->updateStatus($id, 'approved');

                // Assign manager to siege
                $siegeModel = new Siege();
                $siegeModel->updateManager($request['siege_id'], $request['user_id']);

                // Update user role
                $db = Database::getInstance();
                $db->prepare("UPDATE users SET role = 'president_siege' WHERE id = ?")->execute([$request['user_id']]);

                $this->setFlash('success', 'Nouveau responsable de siège assigné.');
            } else {
                $model->updateStatus($id, 'rejected');
                $this->setFlash('info', 'Candidature rejetée.');
            }
        }

        $this->redirect('/assoc/siege-requests');
    }
}
