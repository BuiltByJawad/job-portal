<?php
require_once __DIR__ . '/../app/models/Auth.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/RecruiterController.php';

Auth::startSession();
$route = $_GET['route'] ?? 'home';

$authController = new AuthController();
$recruiterController = new RecruiterController();

switch ($route) {
    case 'auth/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->registerRecruiter();
        } else {
            $authController->showRegister();
        }
        break;

    case 'auth/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case 'auth/logout':
        $authController->logout();
        break;

    case 'recruiter/dashboard':
        $recruiterController->dashboard();
        break;

    default:
        include __DIR__ . '/../app/views/shared/home.php';
        break;
}
