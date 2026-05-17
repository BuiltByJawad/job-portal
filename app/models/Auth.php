<?php
class Auth
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::startSession();
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'is_verified' => (int)$user['is_verified'],
            'is_active' => (int)$user['is_active']
        ];
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    public static function user(): ?array
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireRole(string $role): void
    {
        $user = self::user();
        if (!$user || $user['role'] !== $role) {
            http_response_code(403);
            include __DIR__ . '/../views/shared/403.php';
            exit;
        }
    }
}
