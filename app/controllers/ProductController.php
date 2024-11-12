<?php

namespace App\Controllers;

use App\Models\Product;
use Config\DatabaseConnection;

class ProductController
{
    protected $product;

    public function __construct()
    {
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();
        $this->product = new Product($db);
    }

    public function getAllProducts()
    {
        $products = $this->product->getAllVisibleProducts();
        return json_encode($products); 
    }

    public function getProductDetails($productId)
    {
        $product = $this->product->getProductById($productId);
        
        if ($product) {
            // Decode additional images if stored as JSON
            $product['additional_images'] = explode(',', $product['additional_images'] ?? '');
            return json_encode(['status' => 'success', 'data' => $product]);
        }

        return json_encode(['status' => 'error', 'message' => 'Product not found']);
    }

    public function filterProductsByCategory($categoryId)
    {
        $products = $this->product->getProductsByCategory($categoryId);
        return json_encode($products); 
    }

    public function filterProductsBySellerName($sellerName)
{
    $products = $this->product->getProductsBySellerName($sellerName);
    return json_encode($products);
}

public function filterProductsBySellerID($uuid)
{
    $products = $this->product->getProductsBySellerID($uuid);
    return json_encode($products);
}



    public function searchProducts($query, $minPrice = null, $maxPrice = null, $categoryId = null, $sortBy = null)
    {
        $products = $this->product->advancedSearch($query, $categoryId, $minPrice, $maxPrice, $sortBy);
        return json_encode($products); 
    }
}
