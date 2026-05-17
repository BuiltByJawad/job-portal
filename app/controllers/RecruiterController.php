<?php
require_once __DIR__ . '/../models/Auth.php';

class RecruiterController
{
    public function dashboard(): void
    {
        Auth::requireRole('recruiter');
        $user = Auth::user();
        include __DIR__ . '/../views/recruiter/dashboard.php';
    }
}
