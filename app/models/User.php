<?php
require_once __DIR__ . '/Database.php';

class User
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function createRecruiter(array $data): bool
    {
        $sql = 'INSERT INTO users (name, email, password_hash, phone, role, is_active, is_verified, created_at)
                VALUES (?, ?, ?, ?, "recruiter", 1, 0, NOW())';
        $stmt = $this->db->prepare($sql);
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ssss', $data['name'], $data['email'], $hash, $data['phone']);
        return $stmt->execute();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM users WHERE email = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }
}
