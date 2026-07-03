<?php
/**
 * Wild Rift Meta Tracker — API Entry Point
 * 
 * Bootstraps the application: loads dependencies, registers routes,
 * and dispatches the incoming HTTP request.
 */

// ---------------------------------------------------------------------------
// Error reporting (show all errors in development)
// ---------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');       // Don't leak errors in HTTP response
ini_set('log_errors', '1');           // Log them to PHP error log instead

// ---------------------------------------------------------------------------
// Autoload: require all necessary class files
// ---------------------------------------------------------------------------
$basePath = __DIR__;

// Config
require_once $basePath . '/config/Database.php';

// Models
require_once $basePath . '/models/Champion.php';
require_once $basePath . '/models/Statistic.php';
require_once $basePath . '/models/Counter.php';

// Controllers
require_once $basePath . '/controllers/ChampionController.php';
require_once $basePath . '/controllers/TierListController.php';
require_once $basePath . '/controllers/CounterController.php';

// Router
require_once $basePath . '/Router.php';

// ---------------------------------------------------------------------------
// Bootstrap and dispatch
// ---------------------------------------------------------------------------
try {
    $router = new Router();

    // -----------------------------------------------------------------------
    // Register API routes
    // -----------------------------------------------------------------------

    // Champions
    $router->get('champions', [ChampionController::class, 'index']);
    $router->get('champions/{slug}', [ChampionController::class, 'show']);

    // Tier List
    $router->get('tier-list', [TierListController::class, 'index']);

    // Counters
    $router->get('counters/{slug}', [CounterController::class, 'index']);

    // Stats / Overview
    $router->get('stats/overview', [ChampionController::class, 'overview']);

    // Top leaderboards (win/pick/ban rate)
    $router->get('stats/top-winrate', function () {
        $role  = isset($_GET['role']) ? trim($_GET['role']) : null;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $limit = max(1, min($limit, 50)); // Clamp between 1-50

        $role = ($role !== '' && $role !== null) ? $role : null;

        echo json_encode([
            'success' => true,
            'data'    => Statistic::getTopWinRate($limit, $role),
        ]);
    });

    $router->get('stats/top-pickrate', function () {
        $role  = isset($_GET['role']) ? trim($_GET['role']) : null;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $limit = max(1, min($limit, 50));

        $role = ($role !== '' && $role !== null) ? $role : null;

        echo json_encode([
            'success' => true,
            'data'    => Statistic::getTopPickRate($limit, $role),
        ]);
    });

    $router->get('stats/top-banrate', function () {
        $role  = isset($_GET['role']) ? trim($_GET['role']) : null;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $limit = max(1, min($limit, 50));

        $role = ($role !== '' && $role !== null) ? $role : null;

        echo json_encode([
            'success' => true,
            'data'    => Statistic::getTopBanRate($limit, $role),
        ]);
    });

    // Roles list
    $router->get('roles', function () {
        echo json_encode([
            'success' => true,
            'data'    => Champion::getRoles(),
        ]);
    });

    // -----------------------------------------------------------------------
    // Dispatch the request
    // -----------------------------------------------------------------------
    $router->dispatch();

} catch (PDOException $e) {
    // Database connection or query errors
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error'   => 'Database error occurred.',
        // Include detail only in non-production environments
        'detail'  => $e->getMessage(),
    ]);
} catch (Throwable $e) {
    // Catch-all for any unexpected errors
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error'   => 'An internal server error occurred.',
        'detail'  => $e->getMessage(),
    ]);
}
