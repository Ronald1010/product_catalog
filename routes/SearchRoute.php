<?php

namespace Routes\SearchRoute;

use App\Controllers\SearchController;
use PDO;

class SearchRoute
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function handleSearchRoute($uri, $method)
    {
        // Instantiate the SearchController with the PDO object
        $searchController = new SearchController($this->db);

        // Parse query parameters
        $queryParams = $_GET;

        // Retrieve the JWT token from the Authorization header
        $jwtToken = $_SERVER['HTTP_AUTHORIZATION'] ?? null; // Example: Authorization: Bearer token

        switch (true) {
            // Route for basic search by name
            case strpos($uri, '/search') === 0 && isset($queryParams['query']):
                // Handle basic search by name
                echo $searchController->searchByName($queryParams['query']);
                break;

            default:
                // Invalid route, return error
                echo json_encode(['status' => 'error', 'message' => 'Invalid search route']);
                http_response_code(404);
                break;
        }
    }
}
?>
