<?php
require_once __DIR__ . '/Database.php';

class RecruiterOutreach
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $recruiterId, int $seekerId, int $jobId, string $message): bool
    {
        $sql = 'INSERT INTO recruiter_outreach (recruiter_id, seeker_id, job_id, message, status, sent_at)
                VALUES (?, ?, ?, ?, "sent", NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiis', $recruiterId, $seekerId, $jobId, $message);
        return $stmt->execute();
    }

    public function allByRecruiter(int $recruiterId): array
    {
        $sql = 'SELECT ro.*, u.name AS seeker_name, j.title AS job_title
                FROM recruiter_outreach ro
                JOIN users u ON u.id = ro.seeker_id
                JOIN jobs j ON j.id = ro.job_id
                WHERE ro.recruiter_id = ?
                ORDER BY ro.sent_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $recruiterId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
