<?php

namespace App\Controllers;

use App\Models\Product;
use Config\DatabaseConnection;
use PDOException;
use PDO;

class ProductController
{
    protected $product;
    protected $conn;

    public function __construct()
    {
        // Initialize the database connection
        $dbConnection = new DatabaseConnection();
        $this->conn = $dbConnection->getConnection(); // Set the connection to $conn
        if ($this->conn) {
            error_log("Database connection established successfully.");
        } else {
            error_log("Failed to establish a database connection.");
        }
        $this->product = new Product($this->conn); // Pass $conn to the Product model
    }

    public function getAllProducts()
    {
        $products = $this->product->getAllVisibleProducts();
        
        // Standardize the response format
        $response = [
            'status' => 'success',
            'data' => $products
        ];

        // Return the formatted JSON response
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function getProductDetails($productId)
    {
        $productId = filter_var($productId, FILTER_SANITIZE_NUMBER_INT);
        $product = $this->product->getProductById($productId);

        if ($product) {
            // Add additional images if available
            $product['additional_images'] = explode(',', $product['additional_images'] ?? '');

            // Standardize the response format
            $response = [
                'status' => 'success',
                'data' => $product
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            // Product not found, return an error response
            $response = [
                'status' => 'error',
                'message' => 'Product not found'
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
            http_response_code(404); // Set status code to 404
        }
    }

    public function filterProductsByCategory($categoryId)
    {
        $categoryId = filter_var($categoryId, FILTER_SANITIZE_NUMBER_INT);
        $products = $this->product->getProductsByCategory($categoryId);

        // Standardize the response format
        $response = [
            'status' => 'success',
            'data' => $products
        ];

        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function filterProductsBySellerName($sellerName)
    {
        $sellerName = filter_var($sellerName, FILTER_SANITIZE_STRING);
        $products = $this->product->getProductsBySellerName($sellerName);

        // Standardize the response format
        $response = [
            'status' => 'success',
            'data' => $products
        ];

        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function filterProductsBySellerID($uuid)
    {
        $uuid = filter_var($uuid, FILTER_SANITIZE_STRING);
        $products = $this->product->getProductsBySellerID($uuid);

        // Standardize the response format
        $response = [
            'status' => 'success',
            'data' => $products
        ];

        echo json_encode($response, JSON_PRETTY_PRINT);
    }

public function searchProducts($query = null, $minPrice = null, $maxPrice = null, $category = null, $sortBy = 'product_name', $sortOrder = 'ASC')
{
    // Start with a basic SQL query
    $sql = "SELECT * FROM product";  // Removed 'WHERE visibility = 1' as the 'visibility' column doesn't exist

    // Apply filters if they exist
    $conditions = [];
    if (!empty($query)) {
        $conditions[] = "product_name LIKE :query";  // Filter by product name (use % for LIKE query)
    }
    if (!empty($category)) {
        $conditions[] = "category = :category";  // Filter by category (varchar)
    }
    if (!empty($minPrice)) {
        $conditions[] = "price >= :minPrice";  // Filter by minimum price
    }
    if (!empty($maxPrice)) {
        $conditions[] = "price <= :maxPrice";  // Filter by maximum price
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Validate sort parameters
    $allowedSortFields = ['product_name', 'price', 'created_at'];  // Allowed sort fields
    $allowedSortOrders = ['ASC', 'DESC'];  // Allowed sort orders

    if (!in_array($sortBy, $allowedSortFields)) {
        $sortBy = 'product_name';  // Default sorting by product name if invalid
    }

    if (!in_array($sortOrder, $allowedSortOrders)) {
        $sortOrder = 'ASC';  // Default to ascending order if invalid
    }

    // Apply sorting
    $sql .= " ORDER BY $sortBy $sortOrder";

    // Log the final SQL query for debugging
    error_log("SQL Query: " . $sql);  // Log the query

    // Prepare and execute the query
    $stmt = $this->conn->prepare($sql);

    // Bind parameters
    if (!empty($query)) {
        $stmt->bindValue(':query', '%' . $query . '%');
    }
    if (!empty($category)) {
        $stmt->bindValue(':category', $category);
    }
    if (!empty($minPrice)) {
        $stmt->bindValue(':minPrice', $minPrice);
    }
    if (!empty($maxPrice)) {
        $stmt->bindValue(':maxPrice', $maxPrice);
    }

    // Execute the query
    try {
        $stmt->execute();
        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are results and return JSON response
        if (empty($results)) {
            // No results found, return an error response
            $response = [
                'status' => 'error',
                'message' => 'No results found.'
            ];
        } else {
            // Return results in JSON format
            $response = [
                'status' => 'success',
                'data' => $results
            ];
        }
        echo json_encode($response, JSON_PRETTY_PRINT);  // Return the response as JSON

    } catch (PDOException $e) {
        // Log the detailed error
        error_log("Database Error: " . $e->getMessage());
        
        // Return error response in case of database error
        $response = [
            'status' => 'error',
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);  // Return the error response as JSON
    }
}
}
