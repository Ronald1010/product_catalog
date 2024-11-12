<?php

namespace Routes\SearchRoute;

use App\Controllers\SearchController;

class SearchRoute
{
    public function handleSearchRoute($uri, $method)
    {
        $searchController = new SearchController();

        // Parse query parameters
        $queryParams = $_GET;

        switch (true) {
            // Route for basic search by name
            case strpos($uri, '/search') === 0 && isset($queryParams['query']):
                if (!isset($queryParams['min_price']) && !isset($queryParams['category'])) {
                    $searchController->searchByName($queryParams['query']);
                } elseif (isset($queryParams['min_price']) && isset($queryParams['max_price'])) {
                    // Route for price range search
                    $searchController->searchByPriceRange(
                        $queryParams['query'],
                        $queryParams['min_price'],
                        $queryParams['max_price']
                    );
                } else {
                    // Route for advanced search with category and sorting
                    $searchController->advancedSearch(
                        $queryParams['query'],
                        $queryParams['category'] ?? null,
                        $queryParams['min_price'] ?? 0,
                        $queryParams['max_price'] ?? PHP_INT_MAX,
                        $queryParams['sort'] ?? 'product_name'
                    );
                }
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid search route']);
                http_response_code(404);
                break;
        }
    }
}
