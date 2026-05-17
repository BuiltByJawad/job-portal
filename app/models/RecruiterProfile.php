<?php
require_once __DIR__ . '/Database.php';

class RecruiterProfile
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $userId, array $data): bool
    {
        $sql = 'INSERT INTO recruiter_profiles (user_id, agency_name, specialization, description, website)
                VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'issss',
            $userId,
            $data['agency_name'],
            $data['specialization'],
            $data['description'],
            $data['website']
        );
        return $stmt->execute();
    }

    public function findByUserId(int $userId): ?array
    {
        $sql = 'SELECT * FROM recruiter_profiles WHERE user_id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }
}
