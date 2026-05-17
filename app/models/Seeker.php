<?php
require_once __DIR__ . '/Database.php';

class Seeker
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function search(array $filters): array
    {
        $sql = 'SELECT u.id AS seeker_id, u.name, u.email, sp.headline, sp.skills, sp.years_experience,
                       sp.preferred_location, sp.expected_salary, sp.resume_path
                FROM users u
                JOIN seeker_profiles sp ON sp.user_id = u.id
                WHERE u.role = "seeker" AND u.is_active = 1';

        $params = [];
        $types = '';

        if (!empty($filters['keyword'])) {
            $sql .= ' AND (sp.skills LIKE ? OR sp.headline LIKE ?)';
            $kw = '%' . $filters['keyword'] . '%';
            $params[] = $kw;
            $params[] = $kw;
            $types .= 'ss';
        }

        if (!empty($filters['location'])) {
            $sql .= ' AND sp.preferred_location LIKE ?';
            $params[] = '%' . $filters['location'] . '%';
            $types .= 's';
        }

        if ($filters['min_experience'] !== '' && $filters['min_experience'] !== null) {
            $sql .= ' AND sp.years_experience >= ?';
            $params[] = (float)$filters['min_experience'];
            $types .= 'd';
        }

        if ($filters['max_expected_salary'] !== '' && $filters['max_expected_salary'] !== null) {
            $sql .= ' AND sp.expected_salary <= ?';
            $params[] = (float)$filters['max_expected_salary'];
            $types .= 'd';
        }

        $sql .= ' ORDER BY sp.years_experience DESC, u.name ASC LIMIT 100';

        $stmt = $this->db->prepare($sql);
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
