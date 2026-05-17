<?php
require_once __DIR__ . '/Database.php';

class RecruiterAnalytics
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function summary(int $recruiterId): array
    {
        $outreachSql = 'SELECT COUNT(*) AS total,
                               SUM(CASE WHEN status = "responded" THEN 1 ELSE 0 END) AS responded
                        FROM recruiter_outreach
                        WHERE recruiter_id = ?';
        $stmt = $this->db->prepare($outreachSql);
        $stmt->bind_param('i', $recruiterId);
        $stmt->execute();
        $outreach = $stmt->get_result()->fetch_assoc() ?: ['total' => 0, 'responded' => 0];

        $appSql = 'SELECT COUNT(*) AS total FROM applications a
                   JOIN jobs j ON j.id = a.job_id
                   WHERE j.recruiter_id = ?';
        $stmt2 = $this->db->prepare($appSql);
        $stmt2->bind_param('i', $recruiterId);
        $stmt2->execute();
        $apps = $stmt2->get_result()->fetch_assoc() ?: ['total' => 0];

        $placementSql = 'SELECT COUNT(*) AS total FROM applications a
                         JOIN jobs j ON j.id = a.job_id
                         WHERE j.recruiter_id = ? AND a.status = "hired"';
        $stmt3 = $this->db->prepare($placementSql);
        $stmt3->bind_param('i', $recruiterId);
        $stmt3->execute();
        $placements = $stmt3->get_result()->fetch_assoc() ?: ['total' => 0];

        $outreachTotal = (int)$outreach['total'];
        $responded = (int)$outreach['responded'];
        $appTotal = (int)$apps['total'];
        $placementTotal = (int)$placements['total'];

        return [
            'total_outreach' => $outreachTotal,
            'response_rate' => $outreachTotal > 0 ? round(($responded / $outreachTotal) * 100, 2) : 0,
            'total_applications_managed' => $appTotal,
            'placement_success_rate' => $appTotal > 0 ? round(($placementTotal / $appTotal) * 100, 2) : 0
        ];
    }

    public function byClient(int $recruiterId, ?int $employerId = null): array
    {
        $sql = 'SELECT j.employer_id,
                       COALESCE(ep.company_name, u.name, "Standalone Client") AS client_name,
                       COUNT(DISTINCT j.id) AS total_jobs,
                       COUNT(a.id) AS total_applications,
                       SUM(CASE WHEN a.status = "submitted" THEN 1 ELSE 0 END) AS submitted,
                       SUM(CASE WHEN a.status = "reviewed" THEN 1 ELSE 0 END) AS reviewed,
                       SUM(CASE WHEN a.status = "shortlisted" THEN 1 ELSE 0 END) AS shortlisted,
                       SUM(CASE WHEN a.status = "interview" THEN 1 ELSE 0 END) AS interview,
                       SUM(CASE WHEN a.status = "hired" THEN 1 ELSE 0 END) AS hired
                FROM jobs j
                LEFT JOIN applications a ON a.job_id = j.id
                LEFT JOIN users u ON u.id = j.employer_id
                LEFT JOIN employer_profiles ep ON ep.user_id = u.id
                WHERE j.recruiter_id = ?';

        $types = 'i';
        $params = [$recruiterId];
        if ($employerId) {
            $sql .= ' AND j.employer_id = ?';
            $types .= 'i';
            $params[] = $employerId;
        }

        $sql .= ' GROUP BY j.employer_id, client_name ORDER BY client_name ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
