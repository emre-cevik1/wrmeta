<?php
/**
 * Simple Router
 * 
 * Parses the request URI, matches it against defined routes,
 * and dispatches to the appropriate controller method.
 * Supports static and dynamic route segments (e.g., {slug}).
 */

class Router
{
    /** @var array Registered route definitions */
    private $routes = [];

    /**
     * Register a GET route.
     *
     * @param string   $pattern  URI pattern (e.g., 'champions/{slug}').
     * @param callable $handler  Callback to execute when matched.
     * @return void
     */
    public function get(string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method'  => 'GET',
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Set CORS and Content-Type headers for all responses.
     *
     * @return void
     */
    private function setHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
    }

    /**
     * Handle CORS preflight OPTIONS requests.
     *
     * @return bool True if the request was a preflight and was handled.
     */
    private function handlePreflight(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            return true;
        }
        return false;
    }

    /**
     * Parse the request URI to extract the clean path (strip /api/ prefix and query string).
     *
     * @return string The cleaned path (e.g., 'champions/ahri').
     */
    private function parsePath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remove query string
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        // Strip trailing slash (except root)
        $path = rtrim($path, '/');

        // Strip /api/ or /api prefix
        if (strpos($path, '/api/') === 0) {
            $path = substr($path, 5); // Remove '/api/'
        } elseif ($path === '/api') {
            $path = '';
        } else {
            // Strip leading slash
            $path = ltrim($path, '/');
        }

        return $path;
    }

    /**
     * Match a request path against a route pattern.
     * Supports {param} dynamic segments.
     *
     * @param string $pattern Route pattern (e.g., 'champions/{slug}').
     * @param string $path    Request path (e.g., 'champions/ahri').
     * @param array  $params  Extracted dynamic parameters (by reference).
     * @return bool True if the pattern matches the path.
     */
    private function matchRoute(string $pattern, string $path, array &$params): bool
    {
        $patternParts = explode('/', $pattern);
        $pathParts    = explode('/', $path);

        // Must have the same number of segments
        if (count($patternParts) !== count($pathParts)) {
            return false;
        }

        $params = [];

        foreach ($patternParts as $i => $part) {
            // Dynamic segment: {paramName}
            if (preg_match('/^\{(\w+)\}$/', $part, $matches)) {
                $params[$matches[1]] = $pathParts[$i];
            } elseif ($part !== $pathParts[$i]) {
                // Static segment mismatch
                return false;
            }
        }

        return true;
    }

    /**
     * Dispatch the current HTTP request to the matching route handler.
     * Sets headers, handles preflight, and returns 404 for unmatched routes.
     *
     * @return void
     */
    public function dispatch(): void
    {
        $this->setHeaders();

        // Handle CORS preflight
        if ($this->handlePreflight()) {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $path   = $this->parsePath();

        // Try each registered route
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = [];
            if ($this->matchRoute($route['pattern'], $path, $params)) {
                // Call the handler with extracted parameters
                call_user_func_array($route['handler'], $params);
                return;
            }
        }

        // No route matched — 404
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error'   => 'Route not found.',
            'path'    => $path,
        ]);
    }
}
