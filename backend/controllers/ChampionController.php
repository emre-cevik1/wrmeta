<?php
/**
 * Champion Controller
 * 
 * Handles HTTP requests for champion listing, detail view, and overview stats.
 */

class ChampionController
{
    /**
     * GET /api/champions
     * List all champions with optional ?role= and ?search= filters.
     *
     * @return void Outputs JSON response.
     */
    public static function index(): void
    {
        $role   = isset($_GET['role']) ? trim($_GET['role']) : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;

        // Validate role if provided
        $validRoles = ['baron', 'jungle', 'mid', 'dragon', 'support'];
        if ($role !== null && $role !== '' && !in_array($role, $validRoles, true)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => 'Invalid role. Must be one of: ' . implode(', ', $validRoles),
            ]);
            return;
        }

        // Treat empty strings as null (no filter)
        $role   = ($role !== '' && $role !== null) ? $role : null;
        $search = ($search !== '' && $search !== null) ? $search : null;

        $champions = Champion::getAll($role, $search);

        echo json_encode([
            'success' => true,
            'data'    => $champions,
            'count'   => count($champions),
        ]);
    }

    /**
     * GET /api/champions/{slug}
     * Get a single champion's details including stats and counters.
     *
     * @param string $slug The champion's URL slug.
     * @return void Outputs JSON response.
     */
    public static function show(string $slug): void
    {
        $champion = Champion::getBySlug($slug);

        if ($champion === null) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error'   => 'Champion not found.',
            ]);
            return;
        }

        // Fetch full stat history for this champion
        $stats = Statistic::getByChampionId((int) $champion['id']);

        // Fetch counter/matchup data
        $counters = Counter::getByChampionId((int) $champion['id']);

        echo json_encode([
            'success' => true,
            'data'    => [
                'champion'  => $champion,
                'stats'     => $stats,
                'counters'  => $counters,
            ],
        ]);
    }

    /**
     * GET /api/stats/overview
     * Return high-level overview: total champions, last update, current patch.
     *
     * @return void Outputs JSON response.
     */
    public static function overview(): void
    {
        $db = Database::getInstance();

        // Total champion count
        $totalStmt = $db->query("SELECT COUNT(*) AS total FROM champions");
        $total = (int) $totalStmt->fetch()['total'];

        // Most recent update timestamp
        $updateStmt = $db->query("SELECT MAX(updated_at) AS last_update FROM champions");
        $lastUpdate = $updateStmt->fetch()['last_update'];

        // Current patch (from the most recently scraped statistics)
        $patchStmt = $db->query("
            SELECT patch FROM statistics ORDER BY scraped_at DESC LIMIT 1
        ");
        $patchRow = $patchStmt->fetch();
        $currentPatch = $patchRow ? $patchRow['patch'] : null;

        // Available roles
        $roles = Champion::getRoles();

        // Tier distribution
        $tierStmt = $db->query("
            SELECT tier, COUNT(*) AS count 
            FROM champions 
            WHERE tier IS NOT NULL 
            GROUP BY tier 
            ORDER BY FIELD(tier, 'S+', 'S', 'A', 'B', 'C', 'D')
        ");
        $tierDistribution = $tierStmt->fetchAll();

        echo json_encode([
            'success' => true,
            'data'    => [
                'total_champions'   => $total,
                'last_update'       => $lastUpdate,
                'current_patch'     => $currentPatch,
                'roles'             => $roles,
                'tier_distribution' => $tierDistribution,
            ],
        ]);
    }
}
