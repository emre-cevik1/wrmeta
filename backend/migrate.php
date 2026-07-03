<?php
/**
 * MSSQL Database Migration Script
 * Drops existing tables, creates new MSSQL schema, and seeds data.
 */

require_once __DIR__ . '/config/Database.php';

echo "BasaL: Starting MSSQL Migration...\n\n";

try {
    $db = Database::getInstance();
    
    // 1. Drop existing tables if they exist
    $dropSql = "
        IF OBJECT_ID('scrape_logs', 'U') IS NOT NULL DROP TABLE scrape_logs;
        IF OBJECT_ID('counters', 'U') IS NOT NULL DROP TABLE counters;
        IF OBJECT_ID('statistics', 'U') IS NOT NULL DROP TABLE statistics;
        IF OBJECT_ID('champions', 'U') IS NOT NULL DROP TABLE champions;
    ";
    $db->exec($dropSql);
    echo "[OK] Existing tables dropped.\n";

    // 2. Create tables
    $createSql = "
        CREATE TABLE champions (
            id INT IDENTITY(1,1) PRIMARY KEY,
            name NVARCHAR(100) NOT NULL,
            slug NVARCHAR(100) NOT NULL UNIQUE,
            title NVARCHAR(150),
            role NVARCHAR(50) CHECK (role IN ('baron', 'jungle', 'mid', 'dragon', 'support')),
            image_url NVARCHAR(255),
            tier NVARCHAR(10) CHECK (tier IN ('S+', 'S', 'A', 'B', 'C', 'D')),
            patch NVARCHAR(20) NOT NULL,
            created_at DATETIME DEFAULT GETDATE(),
            updated_at DATETIME DEFAULT GETDATE()
        );

        CREATE TABLE statistics (
            id INT IDENTITY(1,1) PRIMARY KEY,
            champion_id INT NOT NULL,
            role NVARCHAR(50) NOT NULL CHECK (role IN ('baron', 'jungle', 'mid', 'dragon', 'support')),
            win_rate DECIMAL(5,2) NOT NULL,
            pick_rate DECIMAL(5,2) NOT NULL,
            ban_rate DECIMAL(5,2) NOT NULL,
            tier NVARCHAR(10) NOT NULL CHECK (tier IN ('S+', 'S', 'A', 'B', 'C', 'D')),
            patch NVARCHAR(20) NOT NULL,
            scraped_at DATETIME DEFAULT GETDATE(),
            CONSTRAINT fk_stats_champion FOREIGN KEY (champion_id) REFERENCES champions(id) ON DELETE CASCADE
        );

        CREATE TABLE counters (
            id INT IDENTITY(1,1) PRIMARY KEY,
            champion_id INT NOT NULL,
            counter_id INT NOT NULL,
            matchup_type NVARCHAR(20) NOT NULL CHECK (matchup_type IN ('strong_against', 'weak_against')),
            win_rate_diff DECIMAL(5,2) NOT NULL,
            patch NVARCHAR(20) NOT NULL,
            created_at DATETIME DEFAULT GETDATE(),
            CONSTRAINT fk_counter_champ FOREIGN KEY (champion_id) REFERENCES champions(id),
            CONSTRAINT fk_counter_target FOREIGN KEY (counter_id) REFERENCES champions(id),
            CONSTRAINT uq_counter_matchup UNIQUE (champion_id, counter_id, matchup_type)
        );

        CREATE TABLE scrape_logs (
            id INT IDENTITY(1,1) PRIMARY KEY,
            status NVARCHAR(20) NOT NULL CHECK (status IN ('success', 'error', 'running')),
            champions_updated INT DEFAULT 0,
            error_message NVARCHAR(MAX),
            started_at DATETIME DEFAULT GETDATE(),
            completed_at DATETIME NULL
        );
    ";
    $db->exec($createSql);
    echo "[OK] Tables created successfully.\n";

    // 3. Seed data
    $seedFile = __DIR__ . '/../database/seed.sql';
    if (file_exists($seedFile)) {
        $seedSql = file_get_contents($seedFile);
        
        // Split by ';' and execute each statement
        $statements = explode(';', $seedSql);
        $count = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
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
