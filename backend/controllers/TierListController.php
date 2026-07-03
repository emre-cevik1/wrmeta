<?php
/**
 * Tier List Controller
 * 
 * Returns champions grouped by tier for the tier list view.
 */

class TierListController
{
    /**
     * GET /api/tier-list
     * Return all champions grouped by tier (S+, S, A, B, C, D).
     * Supports optional ?role= filter.
     *
     * @return void Outputs JSON response.
     */
    public static function index(): void
    {
        $role = isset($_GET['role']) ? trim($_GET['role']) : null;

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

        $role = ($role !== '' && $role !== null) ? $role : null;

        $db = Database::getInstance();

        // Query champions with their latest stats, ordered by tier priority
        $sql = "
            SELECT 
                c.id,
                c.name,
                c.slug,
                c.title,
                c.role,
                c.image_url,
                c.tier,
                c.patch,
                s.win_rate,
                s.pick_rate,
                s.ban_rate
            FROM champions c
            LEFT JOIN statistics s ON s.champion_id = c.id
                AND s.id = (
                    SELECT s2.id 
                    FROM statistics s2 
                    WHERE s2.champion_id = c.id
        ";

        $params = [];

        if ($role !== null) {
            $sql .= " AND s2.role = :sub_role";
            $params[':sub_role'] = $role;
        }

        $sql .= "
                    ORDER BY s2.scraped_at DESC 
                    LIMIT 1
                )
            WHERE c.tier IS NOT NULL
        ";

        if ($role !== null) {
            $sql .= " AND c.role = :role";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY FIELD(c.tier, 'S+', 'S', 'A', 'B', 'C', 'D'), s.win_rate DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $champions = $stmt->fetchAll();

        // Initialize all tiers with empty arrays so they always appear in response
        $tierList = [
            'S+' => [],
            'S'  => [],
            'A'  => [],
            'B'  => [],
            'C'  => [],
            'D'  => [],
        ];

        // Group champions into their respective tiers
        foreach ($champions as $champion) {
            $tier = $champion['tier'];
            if (isset($tierList[$tier])) {
                $tierList[$tier][] = $champion;
            }
        }

        echo json_encode([
            'success' => true,
            'data'    => $tierList,
            'filters' => [
                'role' => $role,
            ],
        ]);
    }
}
