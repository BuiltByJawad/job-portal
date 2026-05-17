<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/RecruiterClient.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Job.php';

class RecruiterController
{
    public function dashboard(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        include __DIR__ . '/../views/recruiter/dashboard.php';
    }

    public function clients(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        $clientModel = new RecruiterClient();
        $clients = $clientModel->allByRecruiter((int)$user['id']);
        include __DIR__ . '/../views/recruiter/clients.php';
    }

    public function addClient(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();

        $companyName = trim($_POST['company_name_override'] ?? '');
        if ($companyName === '') {
            $error = 'Standalone company name is required for now.';
            $clientModel = new RecruiterClient();
            $clients = $clientModel->allByRecruiter((int)$user['id']);
            include __DIR__ . '/../views/recruiter/clients.php';
            return;
        }

        $clientModel = new RecruiterClient();
        $clientModel->create((int)$user['id'], null, $companyName);
        header('Location: index.php?route=recruiter/clients');
        exit;
    }

    public function showCreateJob(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        $clientModel = new RecruiterClient();
        $categoryModel = new Category();

        $clients = $clientModel->allByRecruiter((int)$user['id']);
        $categories = $categoryModel->all();
        include __DIR__ . '/../views/recruiter/job_create.php';
    }

    public function createJob(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();

        $required = ['client_id', 'category_id', 'title', 'description', 'job_type', 'experience_level', 'deadline', 'status'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $error = 'Please fill all required fields.';
                $this->showCreateJob();
                return;
            }
        }

        $clientModel = new RecruiterClient();
        $clients = $clientModel->allByRecruiter((int)$user['id']);
        $selectedClient = null;
        foreach ($clients as $client) {
            if ((int)$client['id'] === (int)$_POST['client_id']) {
                $selectedClient = $client;
                break;
            }
        }

        if (!$selectedClient) {
            $error = 'Invalid client selected.';
            $this->showCreateJob();
            return;
        }

        // Standalone clients do not have employer account yet; temporarily map to recruiter's user id to satisfy FK.
        $employerId = $selectedClient['employer_id'] ? (int)$selectedClient['employer_id'] : (int)$user['id'];

        $jobModel = new Job();
        $jobModel->createByRecruiter([
            'employer_id' => $employerId,
            'recruiter_id' => (int)$user['id'],
            'category_id' => (int)$_POST['category_id'],
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'requirements' => trim($_POST['requirements'] ?? ''),
            'benefits' => trim($_POST['benefits'] ?? ''),
            'salary_min' => (float)($_POST['salary_min'] ?? 0),
            'salary_max' => (float)($_POST['salary_max'] ?? 0),
            'location' => trim($_POST['location'] ?? ''),
            'job_type' => $_POST['job_type'],
            'experience_level' => $_POST['experience_level'],
            'deadline' => $_POST['deadline'],
            'status' => $_POST['status']
        ]);

        header('Location: index.php?route=recruiter/jobs');
        exit;
    }

    public function jobs(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        $jobModel = new Job();
        $categoryModel = new Category();
        $clientModel = new RecruiterClient();

        $filters = [
            'status' => $_GET['status'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'employer_id' => $_GET['employer_id'] ?? ''
        ];

        $jobs = $jobModel->allByRecruiter((int)$user['id'], $filters);
        $categories = $categoryModel->all();
        $clients = $clientModel->allByRecruiter((int)$user['id']);

        include __DIR__ . '/../views/recruiter/jobs.php';
    }
}
