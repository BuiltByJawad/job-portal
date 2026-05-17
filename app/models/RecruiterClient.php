<?php
require_once __DIR__ . '/Database.php';

class RecruiterClient
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function allByRecruiter(int $recruiterId): array
    {
        $sql = 'SELECT rc.*, u.name AS employer_owner_name, ep.company_name AS employer_company_name
                FROM recruiter_clients rc
                LEFT JOIN users u ON rc.employer_id = u.id
                LEFT JOIN employer_profiles ep ON ep.user_id = u.id
                WHERE rc.recruiter_id = ?
                ORDER BY rc.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $recruiterId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create(int $recruiterId, ?int $employerId, ?string $companyNameOverride): bool
    {
        $sql = 'INSERT INTO recruiter_clients (recruiter_id, employer_id, company_name_override, added_at)
                VALUES (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis', $recruiterId, $employerId, $companyNameOverride);
        return $stmt->execute();
    }
}
