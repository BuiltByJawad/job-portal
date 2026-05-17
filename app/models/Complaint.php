<?php
require_once __DIR__ . '/Database.php';

class Complaint
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $submitterId, int $subjectId, string $description): bool
    {
        $sql = 'INSERT INTO complaints (submitter_id, subject_id, description, status, created_at)
                VALUES (?, ?, ?, "open", NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iis', $submitterId, $subjectId, $description);
        return $stmt->execute();
    }

    public function allBySubmitter(int $submitterId): array
    {
        $sql = 'SELECT c.*, u.name AS subject_name
                FROM complaints c
                JOIN users u ON u.id = c.subject_id
                WHERE c.submitter_id = ?
                ORDER BY c.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $submitterId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
