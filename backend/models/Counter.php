<?php
/**
 * Counter Model
 * 
 * Handles database operations for champion matchup/counter data.
 * Returns structured strong_against and weak_against arrays.
 */

class Counter
{
    /**
     * Get all counters for a champion identified by slug.
     * Returns structured data with strong_against and weak_against arrays.
     *
     * @param string $slug The champion's URL-safe slug.
     * @return array{strong_against: array, weak_against: array}
     */
    public static function getByChampionSlug(string $slug): array
    {
        $db = Database::getInstance();

        // First, resolve the slug to a champion ID
        $stmt = $db->prepare("SELECT id FROM champions WHERE slug = :slug LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $champion = $stmt->fetch();

        if (!$champion) {
            return ['strong_against' => [], 'weak_against' => []];
        }

        return self::getByChampionId((int) $champion['id']);
    }

    /**
     * Get all counters for a champion identified by database ID.
     * Joins with the champions table to get counter champion details.
     *
     * @param int $championId The champion's database ID.
     * @return array{strong_against: array, weak_against: array}
     */
    public static function getByChampionId(int $championId): array
    {
        $db = Database::getInstance();

        $sql = "
            SELECT 
                ct.matchup_type,
                ct.win_rate_diff,
                ct.patch,
                c2.id AS counter_id,
                c2.name AS counter_name,
                c2.slug AS counter_slug,
                c2.image_url AS counter_image_url,
                c2.role AS counter_role
            FROM counters ct
            INNER JOIN champions c2 ON c2.id = ct.counter_id
            WHERE ct.champion_id = :champion_id
            ORDER BY ct.matchup_type ASC, ABS(ct.win_rate_diff) DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':champion_id' => $championId]);
        $rows = $stmt->fetchAll();

        // Partition results into strong_against and weak_against
        $result = [
            'strong_against' => [],
            'weak_against'   => [],
        ];

        foreach ($rows as $row) {
            $entry = [
                'id'            => (int) $row['counter_id'],
                'name'          => $row['counter_name'],
                'slug'          => $row['counter_slug'],
                'image_url'     => $row['counter_image_url'],
                'role'          => $row['counter_role'],
                'win_rate_diff' => (float) $row['win_rate_diff'],
                'patch'         => $row['patch'],
            ];

            if ($row['matchup_type'] === 'strong_against') {
                $result['strong_against'][] = $entry;
            } elseif ($row['matchup_type'] === 'weak_against') {
                $result['weak_against'][] = $entry;
            }
        }

        return $result;
    }
}
