<?php
/**
 * CounterScraper - Fetches champion counter/matchup data.
 *
 * Visits individual champion pages on wildriftfire.com to extract
 * strong_against and weak_against relationships, then upserts them
 * into the counters table.
 *
 * Usage: Instantiated and called by ScrapeRunner.php
 */

require_once __DIR__ . '/../backend/config/Database.php';

class CounterScraper
{
    /** @var PDO Database connection */
    private PDO $db;

    /** @var string Base URL for champion detail pages */
    private string $baseUrl = 'https://wildriftfire.com/champions';

    /** @var string Current patch identifier */
    private string $patch;

    /** @var bool Dry-run mode */
    private bool $dryRun;

    /** @var int Records affected counter */
    private int $recordsAffected = 0;

    /** @var int Delay between HTTP requests in microseconds (800ms) */
    private int $rateLimitUs = 800000;

    /** @var int cURL timeout in seconds */
    private int $timeout = 30;

    /** @var int Max champions to scrape per run (0 = all) */
    private int $limit = 0;

    /**
     * @param string $patch  Current patch version
     * @param bool   $dryRun If true, parse only – do not write to DB
     * @param int    $limit  Max champions to process (0 = unlimited)
     */
    public function __construct(string $patch = '7.1h', bool $dryRun = false, int $limit = 0)
    {
        $database   = new Database();
        $this->db   = $database->getConnection();
        $this->patch  = $patch;
        $this->dryRun = $dryRun;
        $this->limit  = $limit;
    }

    /**
     * Execute the counter scrape pipeline.
     *
     * 1. Load all champion slugs from the database.
     * 2. For each champion, fetch their detail page.
     * 3. Parse counter/matchup sections.
     * 4. Upsert into the counters table.
     *
     * @return int Number of records upserted
     */
    public function run(): int
    {
        $this->log("Starting CounterScraper for patch {$this->patch}");

        // Get all champions we need to scrape counters for
        $champions = $this->loadChampions();

        if (empty($champions)) {
            $this->log("WARNING: No champions found in database. Run seed.sql or TierListScraper first.");
            return 0;
        }

        $total   = count($champions);
        $current = 0;

        $this->log("Processing {$total} champions...");

        foreach ($champions as $champ) {
            $current++;
            $url = "{$this->baseUrl}/{$champ['slug']}";

            $this->log("[{$current}/{$total}] Fetching counters for {$champ['name']}...");

            $html = $this->fetchPage($url);

            if ($html === null) {
                $this->log("  → Skipped (fetch failed).");
                continue;
            }

            $matchups = $this->parseCounters($html, $champ);

            if (empty($matchups)) {
                $this->log("  → No matchup data found.");
                continue;
            }

            $this->log("  → Found " . count($matchups) . " matchups.");

            if (!$this->dryRun) {
                $this->upsertCounters($matchups);
            } else {
                $this->printMatchupPreview($champ['name'], $matchups);
            }

            // Rate limit between champion pages
            usleep($this->rateLimitUs);
        }

        $this->log("Finished. Total counter records affected: {$this->recordsAffected}");
        return $this->recordsAffected;
    }

    /**
     * Load champion id/slug/name from the database.
     *
     * @return array<int, array{id: int, slug: string, name: string}>
     */
    private function loadChampions(): array
    {
        $sql = "SELECT id, slug, name FROM champions ORDER BY id ASC";

        if ($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a champion detail page via cURL.
     *
     * @param  string      $url Full URL to fetch
     * @return string|null Raw HTML or null on failure
     */
    private function fetchPage(string $url): ?string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
                                    . 'AppleWebKit/537.36 (KHTML, like Gecko) '
                                    . 'Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
            ],
            CURLOPT_ENCODING       => '',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            $this->log("  ERROR: HTTP {$httpCode} – {$error}");
            return null;
        }

        return $response;
    }

    /**
     * Parse counter matchup data from a champion detail page.
     *
     * Looks for "strong against" and "weak against" sections in the HTML
     * and extracts opponent champion names and win-rate differentials.
     *
     * @param  string $html  Raw HTML of champion page
     * @param  array  $champ Champion being analyzed {id, slug, name}
     * @return array<int, array{champion_id: int, counter_slug: string, type: string, win_rate_diff: float}>
     */
    private function parseCounters(string $html, array $champ): array
    {
        $matchups = [];

        // Suppress libxml warnings for malformed HTML
        libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);

        $xpath = new DOMXPath($dom);

        // Strategy 1: Look for counter sections with headings
        $matchups = array_merge(
            $matchups,
            $this->parseCounterSection($xpath, $champ, 'strong_against', [
                '//div[contains(@class, "strong")]',
                '//div[contains(@class, "best-against")]',
                '//section[contains(@class, "counter")]//div[contains(@class, "strong")]',
                '//*[contains(@class, "good-against")]',
            ]),
            $this->parseCounterSection($xpath, $champ, 'weak_against', [
                '//div[contains(@class, "weak")]',
                '//div[contains(@class, "worst-against")]',
                '//section[contains(@class, "counter")]//div[contains(@class, "weak")]',
                '//*[contains(@class, "bad-against")]',
            ])
        );

        // Strategy 2: Generic counter-list items
        if (empty($matchups)) {
            $matchups = $this->parseGenericCounterList($xpath, $champ);
        }

        // Strategy 3: JSON embedded data fallback
        if (empty($matchups)) {
            $matchups = $this->parseCountersFromJson($html, $champ);
        }

        libxml_clear_errors();

        return $matchups;
    }

    /**
     * Parse a specific counter section (strong or weak) using multiple XPath patterns.
     *
     * @param  DOMXPath $xpath
     * @param  array    $champ
     * @param  string   $type  'strong_against' or 'weak_against'
     * @param  array    $patterns XPath queries to try
     * @return array
     */
    private function parseCounterSection(DOMXPath $xpath, array $champ, string $type, array $patterns): array
    {
        $matchups = [];

        foreach ($patterns as $pattern) {
            $sections = $xpath->query($pattern);

            if ($sections === false || $sections->length === 0) {
                continue;
            }

            foreach ($sections as $section) {
                // Find champion entries within this section
                $champNodes = $xpath->query(
                    './/a[contains(@href, "/champions/")] | .//div[contains(@class, "champ")] | .//span[contains(@class, "name")]',
                    $section
                );

                if ($champNodes === false) continue;

                foreach ($champNodes as $champNode) {
                    $counterName = trim($champNode->textContent);
                    $counterSlug = null;

                    // Try to extract slug from href
                    if ($champNode instanceof DOMElement && $champNode->hasAttribute('href')) {
                        $href = $champNode->getAttribute('href');
                        if (preg_match('/\/champions\/([a-z0-9\-]+)/i', $href, $m)) {
                            $counterSlug = strtolower($m[1]);
                        }
                    }

                    if (empty($counterName) && empty($counterSlug)) continue;
                    if (empty($counterSlug)) {
                        $counterSlug = $this->slugify($counterName);
                    }

                    // Try to extract win rate diff
                    $wrDiff = 0.0;
                    $wrNode = $xpath->query(
                        './/*[contains(@class, "winrate") or contains(@class, "wr") or contains(@class, "diff")]',
                        $champNode->parentNode ?? $champNode
                    );
                    if ($wrNode && $wrNode->length > 0) {
                        $wrText = trim($wrNode->item(0)->textContent);
                        $wrDiff = (float) preg_replace('/[^0-9.\-]/', '', $wrText);
                    }

                    // Make weak_against diffs negative
                    if ($type === 'weak_against' && $wrDiff > 0) {
                        $wrDiff = -$wrDiff;
                    }

                    $matchups[] = [
                        'champion_id'   => $champ['id'],
                        'counter_slug'  => $counterSlug,
                        'type'          => $type,
                        'win_rate_diff' => $wrDiff,
                    ];
                }
            }

            if (!empty($matchups)) break;
        }

        return $matchups;
    }

    /**
     * Parse generic counter list elements (less structured pages).
     *
     * @param  DOMXPath $xpath
     * @param  array    $champ
     * @return array
     */
    private function parseGenericCounterList(DOMXPath $xpath, array $champ): array
    {
        $matchups = [];

        // Look for any section with "counter" in class or id
        $counterSections = $xpath->query('//*[contains(@class, "counter") or contains(@id, "counter")]');

        if ($counterSections === false || $counterSections->length === 0) {
            return [];
        }

        foreach ($counterSections as $section) {
            $links = $xpath->query('.//a[contains(@href, "/champions/")]', $section);
            if ($links === false) continue;

            $linkCount = $links->length;
            $halfPoint = (int) ceil($linkCount / 2);

            foreach ($links as $i => $link) {
                $href = $link instanceof DOMElement ? $link->getAttribute('href') : '';
                if (preg_match('/\/champions\/([a-z0-9\-]+)/i', $href, $m)) {
                    // First half = strong_against, second half = weak_against (common layout)
                    $type = ($i < $halfPoint) ? 'strong_against' : 'weak_against';
                    $matchups[] = [
                        'champion_id'   => $champ['id'],
                        'counter_slug'  => strtolower($m[1]),
                        'type'          => $type,
                        'win_rate_diff' => ($type === 'weak_against') ? -3.0 : 3.0,
                    ];
                }
            }
        }

        return $matchups;
    }

    /**
     * Extract counter data from embedded JSON in the page.
     *
     * @param  string $html
     * @param  array  $champ
     * @return array
     */
    private function parseCountersFromJson(string $html, array $champ): array
    {
        $matchups = [];

        // Look for __NEXT_DATA__ or similar embedded JSON
        if (preg_match('/<script[^>]*id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/si', $html, $m)) {
            $json = json_decode($m[1], true);
            if ($json) {
                $this->walkJsonForCounters($json, $champ, $matchups);
            }
        }

        return $matchups;
    }

    /**
     * Recursively traverse JSON looking for counter/matchup arrays.
     *
     * @param mixed $data
     * @param array $champ
     * @param array &$matchups
     */
    private function walkJsonForCounters(mixed $data, array $champ, array &$matchups): void
    {
        if (!is_array($data)) return;

        // Look for keys that suggest counter data
        $counterKeys = ['counters', 'matchups', 'strongAgainst', 'weakAgainst', 'best_against', 'worst_against'];

        foreach ($counterKeys as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                $type = str_contains($key, 'weak') || str_contains($key, 'worst')
                    ? 'weak_against'
                    : 'strong_against';

                foreach ($data[$key] as $counter) {
                    if (is_array($counter) && isset($counter['name'])) {
                        $matchups[] = [
                            'champion_id'   => $champ['id'],
                            'counter_slug'  => $this->slugify($counter['slug'] ?? $counter['name']),
                            'type'          => $type,
                            'win_rate_diff' => (float) ($counter['winRateDiff'] ?? ($type === 'weak_against' ? -3.0 : 3.0)),
                        ];
                    } elseif (is_string($counter)) {
                        $matchups[] = [
                            'champion_id'   => $champ['id'],
                            'counter_slug'  => $this->slugify($counter),
                            'type'          => $type,
                            'win_rate_diff' => $type === 'weak_against' ? -3.0 : 3.0,
                        ];
                    }
                }
            }
        }

        // Recurse
        foreach ($data as $value) {
            if (is_array($value)) {
                $this->walkJsonForCounters($value, $champ, $matchups);
            }
        }
    }

    /**
     * Upsert parsed matchup records into the counters table.
     *
     * @param array $matchups
     */
    private function upsertCounters(array $matchups): void
    {
        $sql = "INSERT INTO counters (champion_id, counter_id, matchup_type, win_rate_diff, patch)
                VALUES (
                    :champion_id,
                    (SELECT id FROM champions WHERE slug = :counter_slug LIMIT 1),
                    :matchup_type,
                    :win_rate_diff,
                    :patch
                )
                ON DUPLICATE KEY UPDATE
                    win_rate_diff = VALUES(win_rate_diff),
                    scraped_at    = CURRENT_TIMESTAMP";

        $stmt = $this->db->prepare($sql);

        foreach ($matchups as $m) {
            try {
                $stmt->execute([
                    ':champion_id'   => $m['champion_id'],
                    ':counter_slug'  => $m['counter_slug'],
                    ':matchup_type'  => $m['type'],
                    ':win_rate_diff' => $m['win_rate_diff'],
                    ':patch'         => $this->patch,
                ]);
                $this->recordsAffected++;
            } catch (PDOException $e) {
                // Common: counter champion not in DB yet – skip silently
                if (str_contains($e->getMessage(), 'Column \'counter_id\' cannot be null')) {
                    $this->log("    SKIP: Counter '{$m['counter_slug']}' not found in champions table.");
                } else {
                    $this->log("    ERROR: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Print a quick preview of parsed matchups (dry-run mode).
     *
     * @param string $champName
     * @param array  $matchups
     */
    private function printMatchupPreview(string $champName, array $matchups): void
    {
        $strong = array_filter($matchups, fn($m) => $m['type'] === 'strong_against');
        $weak   = array_filter($matchups, fn($m) => $m['type'] === 'weak_against');

        $this->log("  [DRY RUN] {$champName}:");
        if (!empty($strong)) {
            $slugs = array_column($strong, 'counter_slug');
            $this->log("    Strong against: " . implode(', ', $slugs));
        }
        if (!empty($weak)) {
            $slugs = array_column($weak, 'counter_slug');
            $this->log("    Weak against:   " . implode(', ', $slugs));
        }
    }

    /**
     * Generate a URL-safe slug from a champion name.
     */
    private function slugify(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s_]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Log a message to stdout with timestamp.
     */
    private function log(string $message): void
    {
        $time = date('H:i:s');
        echo "[{$time}] [Counter] {$message}" . PHP_EOL;
    }

    /**
     * Get the base source URL (used by ScrapeRunner for logging).
     */
    public function getSourceUrl(): string
    {
        return $this->baseUrl;
    }
}
