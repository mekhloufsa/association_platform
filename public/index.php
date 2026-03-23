<?php

/**
 * Point d'entrée de l'application (Front Controller)
 */

session_start();

// Activation des erreurs pour le debug XAMPP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload simple (pourra être remplacé par Composer plus tard)
spl_autoload_register(function ($class_name) {
    if (file_exists('../core/' . $class_name . '.php')) {
        require_once '../core/' . $class_name . '.php';
    } else if (file_exists('../controllers/' . $class_name . '.php')) {
        require_once '../controllers/' . $class_name . '.php';
    } else if (file_exists('../models/' . $class_name . '.php')) {
        require_once '../models/' . $class_name . '.php';
    }
});

require_once '../config/database.php';

// Initialisation du routeur
$router = new Router();

// Définition des routes basiques
$router->get('/', 'HomeController@index');
$router->get('/associations', 'AssociationController@index');
$router->get('/association/{slug}', 'AssociationController@show');
$router->get('/annonces', 'AnnonceController@index');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Espace Citoyen
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/help-request', 'DashboardController@helpRequestForm');
$router->post('/dashboard/help-request', 'DashboardController@submitHelpRequest');

$router->get('/dashboard/donation', 'DashboardController@donationForm');
$router->post('/dashboard/donation', 'DashboardController@submitDonation');
$router->get('/dashboard/campaigns', 'DashboardController@campaigns');
$router->post('/dashboard/register-volunteer', 'DashboardController@registerVolunteer');

// Espace Administrateur Central
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/user/delete', 'AdminController@deleteUser');
$router->get('/admin/associations', 'AdminController@associations');
$router->post('/admin/association/delete', 'AdminController@deleteAssociation');
$router->post('/admin/association/validate', 'AdminController@validateAssociation');
$router->get('/admin/help-requests', 'AdminController@helpRequests');
$router->get('/admin/donations', 'AdminController@donations');
$router->get('/admin/material-donations', 'AdminController@materialDonations');
$router->get('/admin/campaigns', 'AdminController@campaigns');
$router->get('/admin/user/{id}', 'AdminController@userDetail');
$router->get('/admin/association/{id}', 'AdminController@associationDetail');
$router->get('/admin/siege/{id}', 'AdminController@siegeDetail');
$router->post('/admin/siege/delete', 'AdminController@deleteSiege');

// Espace Président d'Association
$router->get('/assoc/dashboard', 'AssocController@dashboard');

// Requests & Spaces
$router->get('/request/assoc-create', 'RequestController@createAssociation');
$router->post('/request/assoc-create', 'RequestController@createAssociation');
$router->get('/request/siege-apply', 'RequestController@applyForSiege');
$router->post('/request/siege-apply', 'RequestController@applyForSiege');
$router->get('/admin/requests', 'RequestController@adminRequests');
$router->post('/admin/association-request/review', 'RequestController@reviewAssociation');
$router->get('/assoc/siege-requests', 'RequestController@assocSiegeRequests');
$router->post('/assoc/siege-request/review', 'RequestController@reviewSiege');
$router->get('/dashboard/switch', 'DashboardController@switchSpace');
$router->get('/assoc/help-requests', 'AssocController@helpRequests');
$router->post('/assoc/help-request/status', 'AssocController@updateHelpRequestStatus');
$router->get('/assoc/sieges', 'AssocController@sieges');
$router->get('/assoc/siege/detail/{id}', 'AssocController@siegeDetail');
$router->get('/assoc/add-siege', 'AssocController@addSiege');
$router->post('/assoc/add-siege', 'AssocController@saveSiege');
$router->get('/assoc/siege/edit/{id}', 'AssocController@editSiege');
$router->post('/assoc/siege/update', 'AssocController@updateSiege');
$router->post('/assoc/siege/delete', 'AssocController@deleteSiege');
$router->post('/assoc/siege/remove-manager', 'AssocController@removeSiegeManager');
$router->get('/assoc/campaigns', 'AssocController@campaigns');
$router->get('/assoc/add-campaign', 'AssocController@addCampaign');
$router->post('/assoc/add-campaign', 'AssocController@saveCampaign');
$router->get('/assoc/campaign/{id}', 'AssocController@volunteers');
$router->post('/assoc/volunteer/status', 'AssocController@updateVolunteerStatus');

$router->get('/siege/dashboard', 'SiegeController@dashboard');
$router->get('/siege/help-requests', 'SiegeController@helpRequests');
$router->post('/siege/help-request/status', 'SiegeController@updateHelpRequestStatus');
$router->get('/dashboard/thank-you', 'DashboardController@thankYou');
$router->get('/siege/donations', 'SiegeController@donations');
$router->post('/siege/donation/status', 'SiegeController@updateDonationStatus');
$router->get('/siege/volunteers', 'SiegeController@volunteers');

$router->get('/assoc/settings', 'AssocController@settings');
$router->post('/assoc/settings', 'AssocController@saveSettings');

// Exécution de la route courante
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
