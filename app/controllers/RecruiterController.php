<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/RecruiterClient.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Seeker.php';
require_once __DIR__ . '/../models/RecruiterOutreach.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/RecruiterAnalytics.php';
require_once __DIR__ . '/../models/Complaint.php';
require_once __DIR__ . '/../models/UserDirectory.php';

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
        if ($companyName === '' || strlen($companyName) < 2) {
            $error = 'Company name must be at least 2 characters.';
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

        $salaryMin = (float)($_POST['salary_min'] ?? 0);
        $salaryMax = (float)($_POST['salary_max'] ?? 0);
        if ($salaryMin > 0 && $salaryMax > 0 && $salaryMin > $salaryMax) {
            $error = 'Salary min cannot be greater than salary max.';
            $this->showCreateJob();
            return;
        }

        if (!in_array($_POST['job_type'], ['full-time', 'part-time', 'remote', 'contract'], true)) {
            $error = 'Invalid job type.';
            $this->showCreateJob();
            return;
        }

        if (!in_array($_POST['experience_level'], ['entry', 'mid', 'senior'], true)) {
            $error = 'Invalid experience level.';
            $this->showCreateJob();
            return;
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
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'location' => trim($_POST['location'] ?? ''),
            'job_type' => $_POST['job_type'],
            'experience_level' => $_POST['experience_level'],
            'deadline' => $_POST['deadline'],
            'status' => $_POST['status']
        ]);

        header('Location: index.php?route=recruiter/jobs');
        exit;
    }

    public function jobs(): void { /* unchanged */
        Auth::requireRole('recruiter');
        $user = Auth::user();
        $jobModel = new Job();
        $categoryModel = new Category();
        $clientModel = new RecruiterClient();
        $filters = ['status' => $_GET['status'] ?? '', 'category_id' => $_GET['category_id'] ?? '', 'employer_id' => $_GET['employer_id'] ?? ''];
        $jobs = $jobModel->allByRecruiter((int)$user['id'], $filters);
        $categories = $categoryModel->all();
        $clients = $clientModel->allByRecruiter((int)$user['id']);
        include __DIR__ . '/../views/recruiter/jobs.php';
    }

    public function seekerSearchPage(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $jobModel = new Job(); $jobs = $jobModel->allByRecruiter((int)$user['id']); include __DIR__ . '/../views/recruiter/seeker_search.php'; }
    public function seekerSearchApi(): void { Auth::requireRole('recruiter'); header('Content-Type: application/json'); $filters = ['keyword' => trim($_GET['keyword'] ?? ''), 'location' => trim($_GET['location'] ?? ''), 'min_experience' => $_GET['min_experience'] ?? '', 'max_expected_salary' => $_GET['max_expected_salary'] ?? '']; $seekerModel = new Seeker(); $results = $seekerModel->search($filters); echo json_encode(['success' => true, 'data' => $results]); exit; }

    public function sendOutreach(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();

        $seekerId = (int)($_POST['seeker_id'] ?? 0);
        $jobId = (int)($_POST['job_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($seekerId <= 0 || $jobId <= 0 || $message === '' || strlen($message) < 10 || strlen($message) > 1200) {
            header('Location: index.php?route=recruiter/seekers&error=invalid_outreach');
            exit;
        }

        $outreachModel = new RecruiterOutreach();
        $outreachModel->create((int)$user['id'], $seekerId, $jobId, $message);

        header('Location: index.php?route=recruiter/seekers&success=outreach_sent');
        exit;
    }

    public function outreachList(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $outreachModel = new RecruiterOutreach(); $messages = $outreachModel->allByRecruiter((int)$user['id']); include __DIR__ . '/../views/recruiter/outreach_list.php'; }
    public function applications(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $jobModel = new Job(); $applicationModel = new Application(); $filters = ['job_id' => $_GET['job_id'] ?? '', 'status' => $_GET['status'] ?? '']; $jobs = $jobModel->allByRecruiter((int)$user['id']); $applications = $applicationModel->allForRecruiter((int)$user['id'], $filters); include __DIR__ . '/../views/recruiter/applications.php'; }
    public function updateApplicationStatus(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $applicationId = (int)($_POST['application_id'] ?? 0); $status = trim($_POST['status'] ?? ''); $allowed = ['submitted', 'reviewed', 'shortlisted', 'interview', 'rejected', 'withdrawn', 'hired']; if ($applicationId <= 0 || !in_array($status, $allowed, true)) { header('Location: index.php?route=recruiter/applications&error=invalid_status'); exit; } $applicationModel = new Application(); $applicationModel->updateStatus($applicationId, (int)$user['id'], $status); header('Location: index.php?route=recruiter/applications&success=status_updated'); exit; }
    public function pipeline(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $applicationModel = new Application(); $summary = $applicationModel->pipelineSummary((int)$user['id']); include __DIR__ . '/../views/recruiter/pipeline.php'; }
    public function placements(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $applicationModel = new Application(); $placements = $applicationModel->placementHistory((int)$user['id']); include __DIR__ . '/../views/recruiter/placements.php'; }
    public function analytics(): void { Auth::requireRole('recruiter'); $user = Auth::user(); $analyticsModel = new RecruiterAnalytics(); $clientModel = new RecruiterClient(); $summary = $analyticsModel->summary((int)$user['id']); $clients = $clientModel->allByRecruiter((int)$user['id']); $selectedEmployerId = !empty($_GET['employer_id']) ? (int)$_GET['employer_id'] : null; $clientReport = $analyticsModel->byClient((int)$user['id'], $selectedEmployerId); include __DIR__ . '/../views/recruiter/analytics.php'; }

    public function complaints(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        $directory = new UserDirectory();
        $complaintModel = new Complaint();
        $subjects = $directory->employersAndSeekers();
        $complaints = $complaintModel->allBySubmitter((int)$user['id']);
        include __DIR__ . '/../views/recruiter/complaints.php';
    }

    public function submitComplaint(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();

        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');

        if ($subjectId <= 0 || strlen($description) < 15) {
            header('Location: index.php?route=recruiter/complaints&error=invalid_complaint');
            exit;
        }

        $complaintModel = new Complaint();
        $complaintModel->create((int)$user['id'], $subjectId, $description);
        header('Location: index.php?route=recruiter/complaints&success=complaint_submitted');
        exit;
    }
}
