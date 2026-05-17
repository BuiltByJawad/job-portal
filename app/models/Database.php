<?php
class Database
{
    private static ?mysqli $instance = null;

    public static function connection(): mysqli
    {
        if (self::$instance instanceof mysqli) {
            return self::$instance;
        }

        $config = require __DIR__ . '/../config/config.php';

        $db = new mysqli(
            $config['db_host'],
            $config['db_user'],
            $config['db_pass'],
            $config['db_name'],
            $config['db_port']
        );

        if ($db->connect_error) {
            die('Database connection failed: ' . $db->connect_error);
        }

        $db->set_charset('utf8mb4');
        self::$instance = $db;

        return self::$instance;
    }
}
