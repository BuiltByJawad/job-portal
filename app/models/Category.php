<?php
require_once __DIR__ . '/Database.php';

class Category
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        $sql = 'SELECT id, name FROM categories ORDER BY name ASC';
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
