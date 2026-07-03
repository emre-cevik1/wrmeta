<?php
/**
 * ScrapeRunner - CLI Orchestrator for Wild Rift Meta Tracker scrapers.
 *
 * Coordinates TierListScraper and CounterScraper, manages scrape_log entries,
 * and provides a unified command-line interface.
 *
 * Usage:
 *   php ScrapeRunner.php                   # Full scrape (tier list + counters)
 *   php ScrapeRunner.php --dry-run         # Parse only, no DB writes
 *   php ScrapeRunner.php --tier-only       # Only run tier list scraper
 *   php ScrapeRunner.php --counter-only    # Only run counter scraper
 *   php ScrapeRunner.php --patch=7.1h      # Override patch version
 *   php ScrapeRunner.php --limit=10        # Limit counter scraper to N champions
 *   php ScrapeRunner.php --help            # Show usage
 *
 * Exit Codes:
 *   0 = Success
 *   1 = Partial failure (some scrapers failed)
 *   2 = Total failure (all scrapers failed)
 */

// ── Bootstrap ──────────────────────────────────────────────────────────────────

// Ensure we're running from CLI
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die('This script must be run from the command line.');
}

// Set timezone and error handling
date_default_timezone_set('Europe/Istanbul');
error_reporting(E_ALL);
set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Load dependencies
require_once __DIR__ . '/../backend/config/Database.php';
require_once __DIR__ . '/TierListScraper.php';
require_once __DIR__ . '/CounterScraper.php';

// ── Parse CLI Arguments ────────────────────────────────────────────────────────

$options = getopt('', [
    'dry-run',
    'tier-only',
    'counter-only',
    'patch:',
    'limit:',
    'help',
]);

if (isset($options['help'])) {
    printUsage();
    exit(0);
}

$dryRun      = isset($options['dry-run']);
$tierOnly    = isset($options['tier-only']);
$counterOnly = isset($options['counter-only']);
$patch       = $options['patch'] ?? '7.1h';
$limit       = isset($options['limit']) ? (int) $options['limit'] : 0;

// ── Banner ─────────────────────────────────────────────────────────────────────

echo PHP_EOL;
echo "╔══════════════════════════════════════════════════╗" . PHP_EOL;
echo "║     Wild Rift Meta Tracker – Scrape Runner       ║" . PHP_EOL;
echo "╚══════════════════════════════════════════════════╝" . PHP_EOL;
echo PHP_EOL;

log_msg("Patch:       {$patch}");
log_msg("Dry Run:     " . ($dryRun ? 'YES' : 'no'));
log_msg("Mode:        " . getMode($tierOnly, $counterOnly));
if ($limit > 0) {
    log_msg("Limit:       {$limit} champions (counter scraper)");
}
echo PHP_EOL;

// ── Database Connection ────────────────────────────────────────────────────────

try {
    $database = new Database();
    $db       = $database->getConnection();
    log_msg("Database connection established.");
} catch (PDOException $e) {
    log_msg("FATAL: Database connection failed – " . $e->getMessage());
    exit(2);
}

// ── Create Scrape Log Entry ────────────────────────────────────────────────────

$logId = null;

if (!$dryRun) {
    try {
        $stmt = $db->prepare(
            "INSERT INTO scrape_logs (source_url, status, started_at)
             VALUES (:url, 'running', NOW())"
        );
        $stmt->execute([':url' => 'https://wildriftfire.com']);
        $logId = (int) $db->lastInsertId();
        log_msg("Scrape log created (ID: {$logId}).");
    } catch (PDOException $e) {
        log_msg("WARNING: Could not create scrape log – " . $e->getMessage());
    }
}

// ── Execute Scrapers ───────────────────────────────────────────────────────────

$totalRecords = 0;
$errors       = [];
$startTime    = microtime(true);

// --- Tier List Scraper ---
if (!$counterOnly) {
    log_msg(str_repeat("─", 50));
    log_msg("PHASE 1: Tier List Scraper");
    log_msg(str_repeat("─", 50));

    try {
        $tierScraper = new TierListScraper($patch, $dryRun);
        $tierRecords = $tierScraper->run();
        $totalRecords += $tierRecords;
        log_msg("Tier list phase complete. Records: {$tierRecords}");
    } catch (Throwable $e) {
        $errorMsg = "TierListScraper failed: " . $e->getMessage();
        log_msg("ERROR: {$errorMsg}");
        $errors[] = $errorMsg;
    }

    echo PHP_EOL;
}

// --- Counter Scraper ---
if (!$tierOnly) {
    log_msg(str_repeat("─", 50));
    log_msg("PHASE 2: Counter Scraper");
    log_msg(str_repeat("─", 50));

    try {
        $counterScraper = new CounterScraper($patch, $dryRun, $limit);
        $counterRecords = $counterScraper->run();
        $totalRecords += $counterRecords;
        log_msg("Counter phase complete. Records: {$counterRecords}");
    } catch (Throwable $e) {
        $errorMsg = "CounterScraper failed: " . $e->getMessage();
        log_msg("ERROR: {$errorMsg}");
        $errors[] = $errorMsg;
    }

    echo PHP_EOL;
}

// ── Update Scrape Log ──────────────────────────────────────────────────────────

$elapsed = round(microtime(true) - $startTime, 2);

if (!$dryRun && $logId !== null) {
    try {
        $status       = empty($errors) ? 'success' : 'failed';
        $errorMessage = empty($errors) ? null : implode('; ', $errors);

        $stmt = $db->prepare(
            "UPDATE scrape_logs
                SET status           = :status,
                    records_affected = :records,
                    error_message    = :error,
                    finished_at      = NOW()
              WHERE id = :id"
        );
        $stmt->execute([
            ':status'  => $status,
            ':records' => $totalRecords,
            ':error'   => $errorMessage,
            ':id'      => $logId,
        ]);

        log_msg("Scrape log updated (status: {$status}).");
    } catch (PDOException $e) {
        log_msg("WARNING: Could not update scrape log – " . $e->getMessage());
    }
}

// ── Summary ────────────────────────────────────────────────────────────────────

echo PHP_EOL;
log_msg("══════════════════════════════════════════════════");
log_msg("SUMMARY");
log_msg("══════════════════════════════════════════════════");
log_msg("Status:          " . (empty($errors) ? '✅ SUCCESS' : '❌ FAILED'));
log_msg("Records Affected: {$totalRecords}");
log_msg("Errors:          " . count($errors));
log_msg("Duration:        {$elapsed}s");

if (!empty($errors)) {
    log_msg("");
    log_msg("Error Details:");
    foreach ($errors as $i => $err) {
        log_msg("  " . ($i + 1) . ". {$err}");
    }
}

echo PHP_EOL;

// Close DB connection
Database::close();

// Exit with appropriate code
if (!empty($errors)) {
    $totalPhases = ($tierOnly || $counterOnly) ? 1 : 2;
    exit(count($errors) >= $totalPhases ? 2 : 1);
}

exit(0);

// ── Helper Functions ───────────────────────────────────────────────────────────

/**
 * Print a timestamped log message to stdout.
 */
function log_msg(string $message): void
{
    $time = date('Y-m-d H:i:s');
    echo "[{$time}] {$message}" . PHP_EOL;
}

/**
 * Determine the run mode label.
 */
function getMode(bool $tierOnly, bool $counterOnly): string
{
    if ($tierOnly)    return 'Tier List Only';
    if ($counterOnly) return 'Counters Only';
    return 'Full (Tier List + Counters)';
}

/**
 * Print CLI usage information.
 */
function printUsage(): void
{
    echo <<<'USAGE'

Wild Rift Meta Tracker – Scrape Runner
======================================

Usage:
  php ScrapeRunner.php [OPTIONS]

Options:
  --dry-run         Parse data without writing to the database.
  --tier-only       Only run the Tier List scraper.
  --counter-only    Only run the Counter scraper.
  --patch=VERSION   Set the patch version (default: 7.1h).
  --limit=N         Limit counter scraper to first N champions.
  --help            Show this help message.

Examples:
  php ScrapeRunner.php                          Full scrape
  php ScrapeRunner.php --dry-run                Preview what would be scraped
  php ScrapeRunner.php --tier-only --patch=7.2  Scrape tier list for patch 7.2
  php ScrapeRunner.php --counter-only --limit=5 Scrape counters for 5 champions

Exit Codes:
  0  All scrapers completed successfully
  1  Some scrapers failed
  2  All scrapers failed / fatal error

USAGE;
}
