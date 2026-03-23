<?php

class AuthController extends Controller {
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->render('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                $this->render('auth/login', ['error' => 'Veuillez remplir tous les champs.']);
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                if($user['status'] !== 'active') {
                    $this->render('auth/login', ['error' => 'Votre compte n\'est pas actif.']);
                    return;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];

                switch($user['role']) {
                    case 'admin':
                        $this->redirect('/admin/dashboard');
                        break;
                    case 'president_assoc':
                        $this->redirect('/assoc/dashboard');
                        break;
                    case 'president_siege':
                        $this->redirect('/siege/dashboard');
                        break;
                    default:
                        $this->redirect('/dashboard');
                }
            } else {
                $this->render('auth/login', ['error' => 'Identifiants incorrects.']);
            }
        }
    }

    public function showRegister() {
         if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $wilayaModel = new Wilaya();
        $wilayas = $wilayaModel->findAll();
        $this->render('auth/register', ['wilayas' => $wilayas]);
    }

    public function register() {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'prenom' => filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'password' => $_POST['password'] ?? '',
                'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'wilaya_id' => filter_input(INPUT_POST, 'wilaya_id', FILTER_VALIDATE_INT)
            ];

            if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['password'])) {
                $wilayaModel = new Wilaya();
                $this->render('auth/register', [
                    'error' => 'Veuillez remplir tous les champs obligatoires.',
                    'wilayas' => $wilayaModel->findAll()
                ]);
                return;
            }

            $userModel = new User();
            if ($userModel->findByEmail($data['email'])) {
                $wilayaModel = new Wilaya();
                $this->render('auth/register', [
                    'error' => 'Cet email est déjà utilisé.',
                    'wilayas' => $wilayaModel->findAll()
                ]);
                return;
            }

            $userId = $userModel->create($data);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_role'] = 'user';
                $_SESSION['user_name'] = $data['prenom'] . ' ' . $data['nom'];
                $this->redirect('/dashboard');
            } else {
                 $wilayaModel = new Wilaya();
                $this->render('auth/register', [
                    'error' => 'Erreur lors de la création du compte.',
                    'wilayas' => $wilayaModel->findAll()
                ]);
            }
         }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}
