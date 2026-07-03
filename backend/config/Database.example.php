<?php
/**
 * Database Configuration - Singleton PDO Connection
 * 
 * Provides a single shared PDO instance across the application.
 * Uses MySQL with utf8mb4 charset for full Unicode support.
 */

class Database
{
    /** @var PDO|null */
    private static $instance = null;

    // Connection parameters
    private const HOST     = 'localhost';
    private const DB_NAME  = 'ns1njjns_wr';
    private const USERNAME = 'sa';
    private const PASSWORD = 'your_password_here';
    private const CHARSET  = 'UTF-8';

    /**
     * Prevent direct instantiation (singleton pattern).
     */
    private function __construct() {}

    /**
     * Prevent cloning of the singleton instance.
     */
    private function __clone() {}

    /**
     * Get the shared PDO connection instance.
     * Creates it on first call, reuses it on subsequent calls.
     *
     * @return PDO
     * @throws PDOException If the connection fails.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // MariaDB / MySQL DSN
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::CHARSET;
                
                self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } catch (PDOException $e) {
                throw new PDOException("Connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
