<?php
require_once __DIR__ . '/Database.php';

class Application
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function allForRecruiter(int $recruiterId, array $filters = []): array
    {
        $sql = 'SELECT a.*, u.name AS seeker_name, j.title AS job_title, j.employer_id,
                       COALESCE(ep.company_name, eu.name, "Standalone Client") AS client_name
                FROM applications a
                JOIN jobs j ON j.id = a.job_id
                JOIN users u ON u.id = a.seeker_id
                LEFT JOIN users eu ON eu.id = j.employer_id
                LEFT JOIN employer_profiles ep ON ep.user_id = eu.id
                WHERE j.recruiter_id = ?';

        $types = 'i';
        $params = [$recruiterId];

        if (!empty($filters['job_id'])) {
            $sql .= ' AND a.job_id = ?';
            $types .= 'i';
            $params[] = (int)$filters['job_id'];
        }

        if (!empty($filters['status'])) {
            $sql .= ' AND a.status = ?';
            $types .= 's';
            $params[] = $filters['status'];
        }

        $sql .= ' ORDER BY a.applied_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus(int $applicationId, int $recruiterId, string $status): bool
    {
        $sql = 'UPDATE applications a
                JOIN jobs j ON j.id = a.job_id
                SET a.status = ?
                WHERE a.id = ? AND j.recruiter_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sii', $status, $applicationId, $recruiterId);
        return $stmt->execute();
    }

    public function pipelineSummary(int $recruiterId): array
    {
        $sql = 'SELECT a.status, COUNT(*) AS total
                FROM applications a
                JOIN jobs j ON j.id = a.job_id
                WHERE j.recruiter_id = ? AND a.status IN ("submitted","reviewed","shortlisted","interview")
                GROUP BY a.status';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $recruiterId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $summary = ['submitted' => 0, 'reviewed' => 0, 'shortlisted' => 0, 'interview' => 0];
        foreach ($rows as $row) {
            $summary[$row['status']] = (int)$row['total'];
        }
        return $summary;
    }

    public function placementHistory(int $recruiterId): array
    {
        $sql = 'SELECT a.id, a.applied_at, u.name AS seeker_name, j.title AS job_title,
                       COALESCE(ep.company_name, eu.name, "Standalone Client") AS client_name
                FROM applications a
                JOIN jobs j ON j.id = a.job_id
                JOIN users u ON u.id = a.seeker_id
                LEFT JOIN users eu ON eu.id = j.employer_id
                LEFT JOIN employer_profiles ep ON ep.user_id = eu.id
                WHERE j.recruiter_id = ? AND a.status = "hired"
                ORDER BY a.applied_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $recruiterId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
