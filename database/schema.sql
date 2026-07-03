-- ============================================================
-- Wild Rift Meta Tracker - Database Schema
-- MySQL 8.0+ required
-- ============================================================



-- ============================================================
-- Drop existing tables (reverse order to respect FK constraints)
-- ============================================================
DROP TABLE IF EXISTS scrape_logs;
DROP TABLE IF EXISTS counters;
DROP TABLE IF EXISTS statistics;
DROP TABLE IF EXISTS champions;

-- ============================================================
-- 1. Champions Table
-- Stores core champion identity data and current tier ranking.
-- ============================================================
CREATE TABLE champions (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    slug       VARCHAR(100) NOT NULL,
    title      VARCHAR(200) DEFAULT NULL,
    role       ENUM('baron','jungle','mid','dragon','support') NOT NULL,
    image_url  VARCHAR(500) DEFAULT NULL,
    patch      VARCHAR(20)  DEFAULT NULL,
    tier       ENUM('S+','S','A','B','C','D') DEFAULT 'B',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Unique constraints
    UNIQUE KEY uq_champion_name (name),
    UNIQUE KEY uq_champion_slug (slug),

    -- Search indexes
    INDEX idx_champion_role (role),
    INDEX idx_champion_tier (tier),
    INDEX idx_champion_patch (patch),
    INDEX idx_champion_role_tier (role, tier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. Statistics Table
-- Tracks per-patch, per-role performance metrics for each champion.
-- ============================================================
CREATE TABLE statistics (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    champion_id INT NOT NULL,
    role        ENUM('baron','jungle','mid','dragon','support') NOT NULL,
    win_rate    DECIMAL(5,2) DEFAULT 0.00,
    pick_rate   DECIMAL(5,2) DEFAULT 0.00,
    ban_rate    DECIMAL(5,2) DEFAULT 0.00,
    tier        ENUM('S+','S','A','B','C','D') DEFAULT 'B',
    patch       VARCHAR(20) DEFAULT NULL,
    scraped_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- One stats row per champion+role+patch combination
    UNIQUE KEY unique_champ_role_patch (champion_id, role, patch),

    -- Foreign key
    CONSTRAINT fk_statistics_champion
        FOREIGN KEY (champion_id) REFERENCES champions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Query indexes
    INDEX idx_stats_role (role),
    INDEX idx_stats_patch (patch),
    INDEX idx_stats_tier (tier),
    INDEX idx_stats_win_rate (win_rate DESC),
    INDEX idx_stats_pick_rate (pick_rate DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. Counters Table
-- Records champion-vs-champion matchup relationships.
-- ============================================================
CREATE TABLE counters (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    champion_id   INT NOT NULL,
    counter_id    INT NOT NULL,
    matchup_type  ENUM('strong_against','weak_against') NOT NULL,
    win_rate_diff DECIMAL(5,2) DEFAULT 0.00,
    patch         VARCHAR(20) DEFAULT NULL,
    scraped_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- One row per specific matchup direction per patch
    UNIQUE KEY unique_matchup (champion_id, counter_id, matchup_type, patch),

    -- Foreign keys
    CONSTRAINT fk_counters_champion
        FOREIGN KEY (champion_id) REFERENCES champions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_counters_counter
        FOREIGN KEY (counter_id) REFERENCES champions(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Query indexes
    INDEX idx_counters_champion (champion_id),
    INDEX idx_counters_counter (counter_id),
    INDEX idx_counters_type (matchup_type),
    INDEX idx_counters_patch (patch)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. Scrape Logs Table
-- Audit trail for every scraper execution.
-- ============================================================
CREATE TABLE scrape_logs (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    source_url       VARCHAR(500) DEFAULT NULL,
    status           ENUM('running','success','failed') DEFAULT 'running',
    records_affected INT DEFAULT 0,
    error_message    TEXT DEFAULT NULL,
    started_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    finished_at      TIMESTAMP NULL DEFAULT NULL,

    -- Query indexes
    INDEX idx_logs_status (status),
    INDEX idx_logs_started (started_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
