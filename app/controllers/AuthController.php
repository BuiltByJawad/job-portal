<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/RecruiterProfile.php';
require_once __DIR__ . '/../models/Auth.php';

class AuthController
{
    public function showRegister(): void
    {
        include __DIR__ . '/../views/auth/register_recruiter.php';
    }

    public function registerRecruiter(): void
    {
        $required = ['name', 'email', 'phone', 'password', 'agency_name', 'specialization'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $error = 'Please fill all required fields.';
                include __DIR__ . '/../views/auth/register_recruiter.php';
                return;
            }
        }

        $userModel = new User();
        if ($userModel->findByEmail(trim($_POST['email']))) {
            $error = 'Email already exists.';
            include __DIR__ . '/../views/auth/register_recruiter.php';
            return;
        }

        $userCreated = $userModel->createRecruiter([
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']),
            'password' => $_POST['password']
        ]);

        if (!$userCreated) {
            $error = 'Registration failed.';
            include __DIR__ . '/../views/auth/register_recruiter.php';
            return;
        }

        $user = $userModel->findByEmail(trim($_POST['email']));
        $profileModel = new RecruiterProfile();
        $profileModel->create((int)$user['id'], [
            'agency_name' => trim($_POST['agency_name']),
            'specialization' => trim($_POST['specialization']),
            'description' => trim($_POST['description'] ?? ''),
            'website' => trim($_POST['website'] ?? '')
        ]);

        $success = 'Registration submitted. Wait for admin verification.';
        include __DIR__ . '/../views/auth/login.php';
    }

    public function showLogin(): void
    {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Invalid credentials.';
            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        if ((int)$user['is_active'] !== 1) {
            $error = 'Account is suspended.';
            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        if ($user['role'] === 'recruiter' && (int)$user['is_verified'] !== 1) {
            $error = 'Account pending admin verification.';
            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        Auth::login($user);

        if ($user['role'] === 'recruiter') {
            header('Location: index.php?route=recruiter/dashboard');
            exit;
        }

        header('Location: index.php');
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: index.php?route=auth/login');
        exit;
    }
}
