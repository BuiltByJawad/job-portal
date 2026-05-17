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

    case 'recruiter/clients':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recruiterController->addClient();
        } else {
            $recruiterController->clients();
        }
        break;

    case 'recruiter/jobs/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recruiterController->createJob();
        } else {
            $recruiterController->showCreateJob();
        }
        break;

    case 'recruiter/jobs':
        $recruiterController->jobs();
        break;

    case 'recruiter/seekers':
        $recruiterController->seekerSearchPage();
        break;

    case 'api/recruiter/seekers':
        $recruiterController->seekerSearchApi();
        break;

    case 'recruiter/outreach/send':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recruiterController->sendOutreach();
        }
        break;

    case 'recruiter/outreach':
        $recruiterController->outreachList();
        break;

    default:
        include __DIR__ . '/../app/views/shared/home.php';
        break;
}
