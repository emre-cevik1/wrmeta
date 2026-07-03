<?php
/**
 * TierListScraper - Fetches and parses Wild Rift tier list data.
 *
 * Scrapes champion tier, role, and win-rate information from wildriftfire.com
 * and upserts it into the database. Falls back to existing seed data on failure.
 *
 * Usage: Instantiated and called by ScrapeRunner.php
 */

require_once __DIR__ . '/../backend/config/Database.php';

class TierListScraper
{
    /** @var PDO Database connection */
    private PDO $db;

    /** @var string Target URL for tier list data */
    private string $sourceUrl = 'https://wildriftfire.com/tier-list';

    /** @var string Current patch identifier */
    private string $patch;

    /** @var bool Dry-run mode (no DB writes) */
    private bool $dryRun;

    /** @var int Number of records affected */
    private int $recordsAffected = 0;

    /** @var int Delay between HTTP requests in microseconds (500ms) */
    private int $rateLimitUs = 500000;

    /** @var int cURL timeout in seconds */
    private int $timeout = 30;

    /**
     * @param string $patch  Current patch version
     * @param bool   $dryRun If true, parse only – do not write to DB
     */
    public function __construct(string $patch = '7.1h', bool $dryRun = false)
    {
        $database   = new Database();
        $this->db   = $database->getConnection();
        $this->patch  = $patch;
        $this->dryRun = $dryRun;
    }

    /**
     * Execute the full scrape pipeline.
     *
     * @return int Number of records upserted (0 in dry-run mode)
     */
    public function run(): int
    {
        $this->log("Starting TierListScraper for patch {$this->patch}");
        $this->log("Source: {$this->sourceUrl}");

        // Step 1 – Fetch HTML
        $html = $this->fetchPage($this->sourceUrl);

        if ($html === null) {
            $this->log("WARNING: Could not fetch tier list page. Falling back to seed data.");
            return 0;
        }

        // Step 2 – Parse champion data from DOM
        $champions = $this->parseHtml($html);

        if (empty($champions)) {
            $this->log("WARNING: No champion data parsed. Check if site structure changed.");
            return 0;
        }

        $this->log("Parsed " . count($champions) . " champion entries.");

        // Step 3 – Upsert into database
        if ($this->dryRun) {
            $this->log("[DRY RUN] Skipping database writes.");
            $this->printPreview($champions);
            return 0;
        }

        $this->upsertChampions($champions);
        $this->upsertStatistics($champions);

        $this->log("Finished. Records affected: {$this->recordsAffected}");
        return $this->recordsAffected;
    }

    /**
     * Fetch a page via cURL with proper headers and error handling.
     *
     * @param  string      $url URL to fetch
     * @return string|null Raw HTML or null on failure
     */
    private function fetchPage(string $url): ?string
    {
        $this->log("Fetching: {$url}");

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
                'Cache-Control: no-cache',
            ],
            CURLOPT_ENCODING       => '',  // Accept all encodings
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            $this->log("ERROR: HTTP {$httpCode} – {$error}");
            return null;
        }

        $this->log("Received " . strlen($response) . " bytes (HTTP {$httpCode}).");

        // Rate limit before next request
        usleep($this->rateLimitUs);

        return $response;
    }

    /**
     * Parse tier list HTML using DOMDocument + DOMXPath.
     *
     * Extracts champion name, tier, role, and win rate from the page structure.
     * The exact XPath selectors may need updating when the site redesigns.
     *
     * @param  string $html Raw HTML content
     * @return array<int, array{name: string, slug: string, tier: string, role: string, win_rate: float}>
     */
    private function parseHtml(string $html): array
    {
        $champions = [];

        // Suppress libxml warnings for malformed HTML
        libxml_use_internal_errors(true);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);

        $xpath = new DOMXPath($dom);

        // -- Strategy 1: Look for tier-group sections --
        // wildriftfire typically groups champions by tier (S+, S, A, B, C, D)
        // Each tier section contains champion cards with name, role, and win rate.
        $tierMappings = [
            'S+' => ['s-plus', 's_plus', 'splus', 'S+'],
            'S'  => ['s-tier', 's_tier', 'S'],
            'A'  => ['a-tier', 'a_tier', 'A'],
            'B'  => ['b-tier', 'b_tier', 'B'],
            'C'  => ['c-tier', 'c_tier', 'C'],
            'D'  => ['d-tier', 'd_tier', 'D'],
        ];

        // Try several common DOM patterns used by tier list sites
        $patterns = [
            // Pattern A: div with tier class containing champion items
            '//div[contains(@class, "tier-list")]//div[contains(@class, "champion")]',
            // Pattern B: table rows
            '//table[contains(@class, "tier")]//tr[contains(@class, "champion")]',
            // Pattern C: list items within tier groups
            '//div[contains(@class, "tier")]//a[contains(@class, "champ")]',
            // Pattern D: generic card layout
            '//div[contains(@class, "tierlist")]//div[contains(@class, "card")]',
            // Pattern E: any element with champion data attributes
            '//*[@data-champion]',
        ];

        foreach ($patterns as $pattern) {
            $nodes = $xpath->query($pattern);

            if ($nodes === false || $nodes->length === 0) {
                continue;
            }

            $this->log("Matched DOM pattern: {$pattern} ({$nodes->length} nodes)");

            foreach ($nodes as $node) {
                $entry = $this->extractChampionFromNode($node, $xpath);
                if ($entry !== null) {
                    $champions[] = $entry;
                }
            }

            if (!empty($champions)) {
                break; // Use the first pattern that yields results
            }
        }

        // -- Strategy 2: Regex fallback for JSON-LD or embedded JSON data --
        if (empty($champions)) {
            $this->log("DOM patterns yielded no results. Trying JSON extraction...");
            $champions = $this->extractFromJson($html);
        }

        // -- Strategy 3: Regex fallback for plain-text champion names --
        if (empty($champions)) {
            $this->log("JSON extraction failed. Trying regex fallback...");
            $champions = $this->extractFromRegex($html);
        }

        libxml_clear_errors();

        return $champions;
    }

    /**
     * Extract champion data from a single DOM node.
     *
     * @param  DOMNode       $node  The champion DOM element
     * @param  DOMXPath      $xpath XPath context
     * @return array|null    Parsed champion data or null if insufficient data
     */
    private function extractChampionFromNode(DOMNode $node, DOMXPath $xpath): ?array
    {
        $name    = null;
        $tier    = null;
        $role    = null;
        $winRate = 0.0;

        // Try to get champion name from various attributes/children
        $nameNode = $xpath->query('.//span[contains(@class, "name")] | .//h3 | .//h4 | .//a', $node);
        if ($nameNode && $nameNode->length > 0) {
            $name = trim($nameNode->item(0)->textContent);
        }

        // Fallback: data attributes
        if (empty($name) && $node instanceof DOMElement) {
            $name = $node->getAttribute('data-champion')
                 ?: $node->getAttribute('data-name')
                 ?: $node->getAttribute('title');
        }

        if (empty($name)) {
            return null;
        }

        // Extract tier
        $tierNode = $xpath->query('.//span[contains(@class, "tier")] | ./ancestor::div[contains(@class, "tier")]', $node);
        if ($tierNode && $tierNode->length > 0) {
            $tierText = trim($tierNode->item(0)->textContent);
            $tier = $this->normalizeTier($tierText);
        }

        // Extract role
        $roleNode = $xpath->query('.//span[contains(@class, "role")] | .//img[contains(@class, "role")]', $node);
        if ($roleNode && $roleNode->length > 0) {
            $roleEl = $roleNode->item(0);
            $roleText = $roleEl instanceof DOMElement
                ? ($roleEl->getAttribute('alt') ?: $roleEl->textContent)
                : $roleEl->textContent;
            $role = $this->normalizeRole(trim($roleText));
        }

        // Extract win rate
        $wrNode = $xpath->query('.//*[contains(@class, "win") or contains(@class, "winrate")]', $node);
        if ($wrNode && $wrNode->length > 0) {
            $wrText  = trim($wrNode->item(0)->textContent);
            $winRate = (float) preg_replace('/[^0-9.]/', '', $wrText);
        }

        // Defaults
        if ($tier === null) $tier = 'B';
        if ($role === null) $role = 'mid';

        return [
            'name'     => $name,
            'slug'     => $this->slugify($name),
            'tier'     => $tier,
            'role'     => $role,
            'win_rate' => $winRate > 0 ? $winRate : 50.0,
        ];
    }

    /**
     * Try to extract champion data from embedded JSON (script tags).
     *
     * @param  string $html
     * @return array
     */
    private function extractFromJson(string $html): array
    {
        $champions = [];

        // Look for JSON-LD or __NEXT_DATA__ or similar embedded data
        if (preg_match('/<script[^>]*id="__NEXT_DATA__"[^>]*>(.*?)<\/script>/si', $html, $m)) {
            $json = json_decode($m[1], true);
            if ($json && isset($json['props']['pageProps'])) {
                $pageData = $json['props']['pageProps'];
                // Traverse the data looking for champion arrays
                $this->walkJsonForChampions($pageData, $champions);
            }
        }

        // Generic: look for any large JSON array with champion-like objects
        if (empty($champions) && preg_match_all('/\{["\']name["\']\s*:\s*["\']([^"\']+)["\'].*?["\']tier["\']\s*:\s*["\']([^"\']+)["\']/si', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $champions[] = [
                    'name'     => $match[1],
                    'slug'     => $this->slugify($match[1]),
                    'tier'     => $this->normalizeTier($match[2]),
                    'role'     => 'mid',
                    'win_rate' => 50.0,
                ];
            }
        }

        return $champions;
    }

    /**
     * Recursively walk JSON structure looking for champion data.
     *
     * @param mixed $data
     * @param array &$champions
     */
    private function walkJsonForChampions(mixed $data, array &$champions): void
    {
        if (!is_array($data)) return;

        // Check if this looks like a champion object
        if (isset($data['name']) && (isset($data['tier']) || isset($data['winRate']))) {
            $champions[] = [
                'name'     => $data['name'],
                'slug'     => $this->slugify($data['slug'] ?? $data['name']),
                'tier'     => $this->normalizeTier($data['tier'] ?? 'B'),
                'role'     => $this->normalizeRole($data['role'] ?? $data['lane'] ?? 'mid'),
                'win_rate' => (float) ($data['winRate'] ?? $data['win_rate'] ?? 50.0),
            ];
            return;
        }

        // Recurse into nested arrays
        foreach ($data as $value) {
            if (is_array($value)) {
                $this->walkJsonForChampions($value, $champions);
            }
        }
    }

    /**
     * Last-resort regex extraction for champion names in the HTML.
     *
     * @param  string $html
     * @return array
     */
    private function extractFromRegex(string $html): array
    {
        $champions = [];

        // Look for champion name patterns in links like /champions/{slug}
        if (preg_match_all('/\/champions\/([a-z0-9\-]+)/i', $html, $matches)) {
            $slugs = array_unique($matches[1]);
            foreach ($slugs as $slug) {
                $name = ucwords(str_replace('-', ' ', $slug));
                $champions[] = [
                    'name'     => $name,
                    'slug'     => $slug,
                    'tier'     => 'B',
                    'role'     => 'mid',
                    'win_rate' => 50.0,
                ];
            }
        }

        return $champions;
    }

    /**
     * Upsert champion records into the champions table.
     *
     * @param array $champions Parsed champion data
     */
    private function upsertChampions(array $champions): void
    {
        $sql = "INSERT INTO champions (name, slug, role, patch, tier)
                VALUES (:name, :slug, :role, :patch, :tier)
                ON DUPLICATE KEY UPDATE
                    role    = VALUES(role),
                    patch   = VALUES(patch),
                    tier    = VALUES(tier),
                    updated_at = CURRENT_TIMESTAMP";

        $stmt = $this->db->prepare($sql);

        foreach ($champions as $champ) {
            try {
                $stmt->execute([
                    ':name'  => $champ['name'],
                    ':slug'  => $champ['slug'],
                    ':role'  => $champ['role'],
                    ':patch' => $this->patch,
                    ':tier'  => $champ['tier'],
                ]);
                $this->recordsAffected++;
            } catch (PDOException $e) {
                $this->log("ERROR upserting champion '{$champ['name']}': " . $e->getMessage());
            }
        }

        $this->log("Upserted {$this->recordsAffected} champions.");
    }

    /**
     * Upsert statistics records for each champion.
     *
     * @param array $champions Parsed champion data with win rates
     */
    private function upsertStatistics(array $champions): void
    {
        $sql = "INSERT INTO statistics (champion_id, role, win_rate, tier, patch)
                VALUES (
                    (SELECT id FROM champions WHERE slug = :slug LIMIT 1),
                    :role, :win_rate, :tier, :patch
                )
                ON DUPLICATE KEY UPDATE
                    win_rate   = VALUES(win_rate),
                    tier       = VALUES(tier),
                    scraped_at = CURRENT_TIMESTAMP";

        $stmt   = $this->db->prepare($sql);
        $count  = 0;

        foreach ($champions as $champ) {
            try {
                $stmt->execute([
                    ':slug'     => $champ['slug'],
                    ':role'     => $champ['role'],
                    ':win_rate' => $champ['win_rate'],
                    ':tier'     => $champ['tier'],
                    ':patch'    => $this->patch,
                ]);
                $count++;
            } catch (PDOException $e) {
                $this->log("ERROR upserting stats for '{$champ['name']}': " . $e->getMessage());
            }
        }

        $this->recordsAffected += $count;
        $this->log("Upserted {$count} statistics records.");
    }

    /**
     * Normalize a tier string to a valid ENUM value.
     */
    private function normalizeTier(string $raw): string
    {
        $raw = strtoupper(trim($raw));

        $map = [
            'S+' => 'S+', 'S PLUS' => 'S+', 'SPLUS' => 'S+',
            'S'  => 'S',
            'A'  => 'A',
            'B'  => 'B',
            'C'  => 'C',
            'D'  => 'D',
        ];

        // Try direct mapping first
        if (isset($map[$raw])) return $map[$raw];

        // Try extracting just the letter/grade
        if (preg_match('/^(S\+|[SABCD])/i', $raw, $m)) {
            $key = strtoupper($m[1]);
            return $map[$key] ?? 'B';
        }

        return 'B'; // safe default
    }

    /**
     * Normalize a role string to a valid ENUM value.
     */
    private function normalizeRole(string $raw): string
    {
        $raw = strtolower(trim($raw));

        $map = [
            'baron'   => 'baron',   'top' => 'baron', 'solo' => 'baron', 'baron lane' => 'baron',
            'jungle'  => 'jungle',  'jg'  => 'jungle', 'jungler' => 'jungle',
            'mid'     => 'mid',     'middle' => 'mid', 'mid lane' => 'mid',
            'dragon'  => 'dragon',  'adc' => 'dragon', 'bot' => 'dragon', 'marksman' => 'dragon', 'dragon lane' => 'dragon',
            'support' => 'support', 'sup' => 'support', 'supp' => 'support',
        ];

        return $map[$raw] ?? 'mid';
    }

    /**
     * Generate a URL-safe slug from a champion name.
     */
    private function slugify(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);   // remove special chars
        $slug = preg_replace('/[\s_]+/', '-', $slug);          // spaces/underscores → hyphens
        $slug = preg_replace('/-+/', '-', $slug);              // collapse multiple hyphens
        return trim($slug, '-');
    }

    /**
     * Print a table preview of parsed data (for dry-run mode).
     *
     * @param array $champions
     */
    private function printPreview(array $champions): void
    {
        $this->log(str_pad("CHAMPION", 20) . str_pad("ROLE", 10) . str_pad("TIER", 6) . "WIN RATE");
        $this->log(str_repeat("─", 50));

        foreach (array_slice($champions, 0, 30) as $c) {
            $this->log(
                str_pad($c['name'], 20) .
                str_pad($c['role'], 10) .
                str_pad($c['tier'], 6) .
                $c['win_rate'] . '%'
            );
        }

        if (count($champions) > 30) {
            $this->log("... and " . (count($champions) - 30) . " more.");
        }
    }

    /**
     * Log a message to stdout with timestamp.
     */
    private function log(string $message): void
    {
        $time = date('H:i:s');
        echo "[{$time}] [TierList] {$message}" . PHP_EOL;
    }

    /**
     * Get the source URL (used by ScrapeRunner for logging).
     */
    public function getSourceUrl(): string
    {
        return $this->sourceUrl;
    }
}
