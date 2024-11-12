<?php

namespace App\Controllers;

use App\Models\Product;
use Config\DatabaseConnection;

class SearchController
{
    protected $product;

    public function __construct()
    {
        // Initialize the database connection and pass it to the Product model
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();
        if ($db) {
            error_log("Database connection established successfully in SearchController.");
        } else {
            error_log("Failed to establish a database connection in SearchController.");
        }
        $this->product = new Product($db);
    }

    // 1. Search products by name
    public function searchByName($query)
{
    $results = $this->product->searchByName($query);

    // Transform an empty data array to a custom message
    if (empty($results)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'No products found for the given search criteria'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => $results
        ]);
    }
}




    // 2. Search products with a price range
    public function searchByPriceRange($query, $minPrice, $maxPrice)
    {
        error_log("Entering SearchController::searchByPriceRange() with query: $query, minPrice: $minPrice, maxPrice: $maxPrice");

        $results = $this->product->searchByPriceRange($query, $minPrice, $maxPrice);

        error_log("Results returned to SearchController::searchByPriceRange(): " . print_r($results, true));

        echo json_encode([
            'status' => 'success',
            'query' => $query,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'data' => $results
        ]);

        error_log("Exiting SearchController::searchByPriceRange()");
    }

    // 3. Advanced search with filters for category, price range, and sorting
    public function advancedSearch($query, $category, $minPrice, $maxPrice, $sortBy)
    {
        error_log("Entering SearchController::advancedSearch() with query: $query, category: $category, minPrice: $minPrice, maxPrice: $maxPrice, sortBy: $sortBy");

        $results = $this->product->advancedSearch($query, $category, $minPrice, $maxPrice, $sortBy);

        error_log("Results returned to SearchController::advancedSearch(): " . print_r($results, true));

        echo json_encode([
            'status' => 'success',
            'query' => $query,
            'category' => $category,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'sort' => $sortBy,
            'data' => $results
        ]);

        error_log("Exiting SearchController::advancedSearch()");
    }
}
