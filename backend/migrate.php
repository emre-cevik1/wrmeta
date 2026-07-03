<?php
/**
 * MariaDB / MySQL Database Migration Script
 * Drops existing tables, creates new schema, and seeds data.
 */

require_once __DIR__ . '/config/Database.php';

echo "WR META: Starting MariaDB/MySQL Migration...\n\n";

try {
    $db = Database::getInstance();
    
    // 1. Drop existing tables if they exist
    $dropSql = "
        SET FOREIGN_KEY_CHECKS = 0;
        DROP TABLE IF EXISTS scrape_logs;
        DROP TABLE IF EXISTS counters;
        DROP TABLE IF EXISTS statistics;
        DROP TABLE IF EXISTS champions;
        SET FOREIGN_KEY_CHECKS = 1;
    ";
    $db->exec($dropSql);
    echo "[OK] Existing tables dropped.\n";

    // 2. Create tables
    $createSql = "
        CREATE TABLE champions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            title VARCHAR(150),
            role ENUM('baron', 'jungle', 'mid', 'dragon', 'support'),
            image_url VARCHAR(255),
            tier ENUM('S+', 'S', 'A', 'B', 'C', 'D'),
            patch VARCHAR(20) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE statistics (
            id INT AUTO_INCREMENT PRIMARY KEY,
            champion_id INT NOT NULL,
            role ENUM('baron', 'jungle', 'mid', 'dragon', 'support') NOT NULL,
            win_rate DECIMAL(5,2) NOT NULL,
            pick_rate DECIMAL(5,2) NOT NULL,
            ban_rate DECIMAL(5,2) NOT NULL,
            tier ENUM('S+', 'S', 'A', 'B', 'C', 'D') NOT NULL,
            patch VARCHAR(20) NOT NULL,
            scraped_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (champion_id) REFERENCES champions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE counters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            champion_id INT NOT NULL,
            counter_id INT NOT NULL,
            matchup_type ENUM('strong_against', 'weak_against') NOT NULL,
            win_rate_diff DECIMAL(5,2) NOT NULL,
            patch VARCHAR(20) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (champion_id) REFERENCES champions(id),
            FOREIGN KEY (counter_id) REFERENCES champions(id),
            UNIQUE KEY uq_counter_matchup (champion_id, counter_id, matchup_type)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE scrape_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            status ENUM('success', 'error', 'running') NOT NULL,
            champions_updated INT DEFAULT 0,
            error_message TEXT,
            started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            completed_at DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $db->exec($createSql);
    echo "[OK] Tables created successfully.\n";

    // 3. Seed data
    $seedFile = __DIR__ . '/../database/seed.sql';
    if (file_exists($seedFile)) {
        $seedSql = file_get_contents($seedFile);
        
        // Split by ';' and execute each statement
        // Note: For large seed files, multiple queries can be combined or we can just run it as a single block if driver supports it.
        // PDO default settings usually don't support multi-query via exec well for large dumps, so we split.
        $statements = array_filter(array_map('trim', explode(';', $seedSql)));
        
        $count = 0;
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $db->exec($statement);
                $count++;
            }
        }
        echo "[OK] Seed data inserted ($count statements).\n";
    } else {
        echo "[WARNING] Seed file not found at: $seedFile\n";
    }

    echo "\nMigration completed successfully! 🎉\n";

} catch (PDOException $e) {
    echo "\n[ERROR] Migration failed: " . $e->getMessage() . "\n";
    die(1);
}
