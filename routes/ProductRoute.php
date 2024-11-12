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
            if (isset($_GET['category'])) { // Check for category filter
                $category = $_GET['category'];
                echo $productController->filterProductsByCategory($category);
            } elseif (isset($_GET['seller_name'])) { // Check for full seller name filter
                $sellerName = $_GET['seller_name'];
                echo $productController->filterProductsBySellerName($sellerName);
            } elseif (isset($_GET['seller_id'])) { // Check for seller UUID filter
                    $sellerUuid = $_GET['seller_id'];
                    echo $productController->filterProductsBySellerID($sellerUuid);
            } elseif ($uri === '') { // GET - List all products
                echo $productController->getAllProducts();
            } elseif ($uri === '/search') { // GET - Search products
                $query = $_GET['query'] ?? null;
                $categoryId = $_GET['category'] ?? null;
                $minPrice = $_GET['min_price'] ?? null;
                $maxPrice = $_GET['max_price'] ?? null;
                $sortBy = $_GET['sort'] ?? null;
                echo $productController->searchProducts($query, $minPrice, $maxPrice, $categoryId, $sortBy);
            } elseif (preg_match('/\/(\d+)/', $uri, $matches)) { // GET - Product details by ID
                $productId = $matches[1];
                echo $productController->getProductDetails($productId);
            } else {
                $this->sendErrorResponse('Route not found', 404);
            }
        }
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
