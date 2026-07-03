<?php
/**
 * Champion Model
 * 
 * Handles all database operations related to champions.
 * Joins with the statistics table to provide tier/rate data.
 */

class Champion
{
    /**
     * Get all champions with optional role and search filters.
     * Joins with the latest statistics row per champion for rate data.
     *
     * @param string|null $role   Filter by role (baron, jungle, mid, dragon, support).
     * @param string|null $search Search champion name (partial match).
     * @return array List of champion records.
     */
    public static function getAll(?string $role = null, ?string $search = null): array
    {
        $db = Database::getInstance();

        // Base query: join champions with their latest statistics
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

        // If filtering by role, also filter the statistics subquery by role
        if ($role !== null) {
            $sql .= " AND s2.role = :sub_role";
            $params[':sub_role'] = $role;
        }

        $sql .= "
                    ORDER BY s2.scraped_at DESC 
                    OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
                )
        ";

        // WHERE clause filters on the outer query
        $conditions = [];

        if ($role !== null) {
            $conditions[] = "c.role = :role";
            $params[':role'] = $role;
        }

        if ($search !== null) {
            $conditions[] = "c.name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY c.name ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Get a single champion by its URL slug, including latest statistics.
     *
     * @param string $slug The champion's URL-safe slug.
     * @return array|null Champion record or null if not found.
     */
    public static function getBySlug(string $slug): ?array
    {
        $db = Database::getInstance();

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
                c.created_at,
                c.updated_at,
                s.win_rate,
                s.pick_rate,
                s.ban_rate,
                s.role AS stats_role,
                s.tier AS stats_tier,
                s.patch AS stats_patch
            FROM champions c
            LEFT JOIN statistics s ON s.champion_id = c.id
                AND s.id = (
                    SELECT s2.id 
                    FROM statistics s2 
                    WHERE s2.champion_id = c.id 
                    ORDER BY s2.scraped_at DESC 
                    OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
                )
            WHERE c.slug = :slug
            ORDER BY c.id ASC
            OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Get all distinct champion roles from the database.
     *
     * @return array List of role strings.
     */
    public static function getRoles(): array
    {
        $db = Database::getInstance();

        $sql = "SELECT DISTINCT role FROM champions WHERE role IS NOT NULL ORDER BY role ASC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
