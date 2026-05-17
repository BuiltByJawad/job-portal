<?php
require_once __DIR__ . '/Database.php';

class UserDirectory
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function employersAndSeekers(): array
    {
        $sql = 'SELECT id, name, role FROM users WHERE role IN ("employer","seeker") AND is_active = 1 ORDER BY role, name';
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
