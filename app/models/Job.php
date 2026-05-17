<?php
require_once __DIR__ . '/Database.php';

class Job
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function createByRecruiter(array $data): bool
    {
        $sql = 'INSERT INTO jobs (
                    employer_id, recruiter_id, category_id, title, description, requirements, benefits,
                    salary_min, salary_max, location, job_type, experience_level, deadline, status, is_featured, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'iiissssddsssss',
            $data['employer_id'],
            $data['recruiter_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['requirements'],
            $data['benefits'],
            $data['salary_min'],
            $data['salary_max'],
            $data['location'],
            $data['job_type'],
            $data['experience_level'],
            $data['deadline'],
            $data['status']
        );

        return $stmt->execute();
    }

    public function allByRecruiter(int $recruiterId, array $filters = []): array
    {
        $sql = 'SELECT j.*, c.name AS category_name,
                       COALESCE(ep.company_name, u.name, "Standalone Client") AS company_name
                FROM jobs j
                JOIN categories c ON j.category_id = c.id
                LEFT JOIN users u ON j.employer_id = u.id
                LEFT JOIN employer_profiles ep ON ep.user_id = u.id
                WHERE j.recruiter_id = ?';

        $params = [$recruiterId];
        $types = 'i';

        if (!empty($filters['status'])) {
            $sql .= ' AND j.status = ?';
            $params[] = $filters['status'];
            $types .= 's';
        }

        if (!empty($filters['category_id'])) {
            $sql .= ' AND j.category_id = ?';
            $params[] = (int)$filters['category_id'];
            $types .= 'i';
        }

        if (!empty($filters['employer_id'])) {
            $sql .= ' AND j.employer_id = ?';
            $params[] = (int)$filters['employer_id'];
            $types .= 'i';
        }

        $sql .= ' ORDER BY j.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
