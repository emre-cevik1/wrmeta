<?php
/**
 * Counter Controller
 * 
 * Handles HTTP requests for champion counter/matchup data.
 */

class CounterController
{
    /**
     * GET /api/counters/{slug}
     * Return strong_against and weak_against matchup data for a champion.
     *
     * @param string $slug The champion's URL slug.
     * @return void Outputs JSON response.
     */
    public static function index(string $slug): void
    {
        // Validate slug format (alphanumeric + hyphens only)
        if (!preg_match('/^[a-z0-9\-]+$/i', $slug)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error'   => 'Invalid champion slug format.',
            ]);
            return;
        }

        // Verify the champion exists
        $champion = Champion::getBySlug($slug);

        if ($champion === null) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error'   => 'Champion not found.',
            ]);
            return;
        }

        // Fetch counter data
        $counters = Counter::getByChampionSlug($slug);

        echo json_encode([
            'success' => true,
            'data'    => [
                'champion' => [
                    'id'        => (int) $champion['id'],
                    'name'      => $champion['name'],
                    'slug'      => $champion['slug'],
                    'image_url' => $champion['image_url'],
                ],
                'strong_against' => $counters['strong_against'],
                'weak_against'   => $counters['weak_against'],
                'total_matchups' => count($counters['strong_against']) + count($counters['weak_against']),
            ],
        ]);
    }
}
