<?php
/**
 * Statistic Model
 * 
 * Handles database operations for champion statistics (win/pick/ban rates).
 * Provides leaderboard queries for top-performing champions.
 */

class Statistic
{
    /**
     * Get all statistics for a specific champion, optionally filtered by role.
     * Returns historical stat entries ordered by most recent first.
     *
     * @param int         $championId The champion's database ID.
     * @param string|null $role       Optional role filter.
     * @return array List of statistic records.
     */
    public static function getByChampionId(int $championId, ?string $role = null): array
    {
        $db = Database::getInstance();

        $sql = "
            SELECT 
                s.id,
                s.champion_id,
                s.role,
                s.win_rate,
                s.pick_rate,
                s.ban_rate,
                s.tier,
                s.patch,
                s.scraped_at
            FROM statistics s
            WHERE s.champion_id = :champion_id
        ";

        $params = [':champion_id' => $championId];

        if ($role !== null) {
            $sql .= " AND s.role = :role";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY s.scraped_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Get champions with the highest win rates.
     * Uses the latest statistics entry per champion.
     *
     * @param int         $limit Max results to return (default 10).
     * @param string|null $role  Optional role filter.
     * @return array Ranked list of champions by win rate.
     */
    public static function getTopWinRate(int $limit = 10, ?string $role = null): array
    {
        return self::getTopByMetric('win_rate', $limit, $role);
    }

    /**
     * Get champions with the highest pick rates.
     *
     * @param int         $limit Max results to return (default 10).
     * @param string|null $role  Optional role filter.
     * @return array Ranked list of champions by pick rate.
     */
    public static function getTopPickRate(int $limit = 10, ?string $role = null): array
    {
        return self::getTopByMetric('pick_rate', $limit, $role);
    }

    /**
     * Get champions with the highest ban rates.
     *
     * @param int         $limit Max results to return (default 10).
     * @param string|null $role  Optional role filter.
     * @return array Ranked list of champions by ban rate.
     */
    public static function getTopBanRate(int $limit = 10, ?string $role = null): array
    {
        return self::getTopByMetric('ban_rate', $limit, $role);
    }

    /**
     * Internal helper: get top champions by a specific metric column.
     * Joins with champions table and picks the latest stat row per champion.
     *
     * @param string      $metric Column name (win_rate, pick_rate, ban_rate).
     * @param int         $limit  Max results.
     * @param string|null $role   Optional role filter.
     * @return array Ranked champion list.
     */
    private static function getTopByMetric(string $metric, int $limit, ?string $role): array
    {
        $db = Database::getInstance();

        // Whitelist the metric column to prevent SQL injection
        $allowedMetrics = ['win_rate', 'pick_rate', 'ban_rate'];
        if (!in_array($metric, $allowedMetrics, true)) {
            throw new InvalidArgumentException("Invalid metric: {$metric}");
        }

        $sql = "
            SELECT 
                c.id,
                c.name,
                c.slug,
                c.title,
                c.role,
                c.image_url,
                c.tier,
                s.win_rate,
                s.pick_rate,
                s.ban_rate,
                s.patch,
                s.role AS stats_role
            FROM statistics s
            INNER JOIN champions c ON c.id = s.champion_id
            WHERE s.id = (
                SELECT s2.id 
                FROM statistics s2 
                WHERE s2.champion_id = s.champion_id
        ";

        $params = [];

        if ($role !== null) {
            $sql .= " AND s2.role = :sub_role";
            $params[':sub_role'] = $role;
        }

        $sql .= "
                ORDER BY s2.scraped_at DESC 
                OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
            )
        ";

        if ($role !== null) {
            $sql .= " AND c.role = :role";
            $params[':role'] = $role;
        }

        // Use whitelisted column name directly (safe — validated above)
        $sql .= " ORDER BY s.{$metric} DESC OFFSET 0 ROWS FETCH NEXT :limit ROWS ONLY";
        $params[':limit'] = $limit;

        $stmt = $db->prepare($sql);

        // Bind limit as integer explicitly
        foreach ($params as $key => $value) {
            if ($key === ':limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
