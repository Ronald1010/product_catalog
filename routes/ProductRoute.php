<?php

namespace Routes\ProductRoute;

use App\Controllers\ProductController;

class ProductRoute
{
    public function handleProductRoute($uri, $method)
    {
        $productController = new ProductController();

        // Normalize URI for accurate matching
        $uri = str_replace('/products', '', $uri);

        if ($method === 'GET') {
            // Search products route
            if ($uri === '/search') {
                $this->handleSearchRoute($productController);
            } 
            // Filter by category
            elseif (isset($_GET['category'])) {
                $category = $_GET['category'];
                echo $productController->filterProductsByCategory($category);
            } 
            // Filter by seller name
            elseif (isset($_GET['seller_name'])) {
                $sellerName = $_GET['seller_name'];
                echo $productController->filterProductsBySellerName($sellerName);
            } 
            // Filter by seller UUID
            elseif (isset($_GET['seller_id'])) {
                $sellerUuid = $_GET['seller_id'];
                echo $productController->filterProductsBySellerID($sellerUuid);
            } 
            // List all products
            elseif ($uri === '') { 
                echo $productController->getAllProducts();
            } 
            // Get product details by ID
            elseif (preg_match('/\/(\d+)/', $uri, $matches)) { 
                $productId = $matches[1];
                echo $productController->getProductDetails($productId);
            } 
            else {
                $this->sendErrorResponse('Route not found', 404);
            }
        }
    }

    private function handleSearchRoute($productController)
    {
        // Parse query parameters for search
        $query = $_GET['query'] ?? null;
        $category = $_GET['category'] ?? null;
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;
        $sortBy = $_GET['sort'] ?? 'product_name'; // Default sort by product name
        $sortOrder = $_GET['sort_order'] ?? 'ASC'; // Default order ascending

        // Validate required query parameter
        if (!$query) {
            $this->sendErrorResponse('Query parameter is required for search', 400);
            return;
        }

        // Call the searchProducts method with parameters
        echo $productController->searchProducts($query, $minPrice, $maxPrice, $category, $sortBy, $sortOrder);
    }

    private function sendErrorResponse($message, $statusCode = 400)
    {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        http_response_code($statusCode);
    }
}
