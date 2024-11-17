<?php

namespace App\Models;

use PDO;

class Product
{
    private $conn;
    private $table = 'product';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
        // error_log("Product model instantiated with database connection.");
    }


    /* ====== SECTION 1: Getter Methods ====== */

    // Retrieve all visible products with primary image and seller's name
    public function getAllVisibleProducts($category = null, $minPrice = null, $maxPrice = null, $sortBy = 'product_name', $sortOrder = 'ASC')
    {
        $query = "SELECT * FROM {$this->table} WHERE 1";
        // Apply category filter if provided
        if ($category) {
            $query .= " AND category LIKE :category";
        }
        // Apply price filters if provided
        if ($minPrice !== null) {
            $query .= " AND price >= :minPrice";
        }
        if ($maxPrice !== null) {
            $query .= " AND price <= :maxPrice";
        }
        // Sorting
        $query .= " ORDER BY $sortBy $sortOrder";
    
        $stmt = $this->conn->prepare($query);
        // Bind parameters
        if ($category) {
            $stmt->bindValue(':category', "%$category%", PDO::PARAM_STR);
        }
        if ($minPrice !== null) {
            $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
        }
        if ($maxPrice !== null) {
            $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_STR);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    



    // Retrieve details for a specific product by ID, including additional images
    public function getSellerProducts($sellerId)
{
    $query = "SELECT p.product_id, p.product_name, p.description, p.price, 
                     p.stock_quantity AS stock, p.category, p.size, p.color, 
                     p.product_image AS primary_image, CONCAT(u.first_name, ' ', u.last_name) AS seller_name
              FROM product p
              JOIN users u ON p.seller_id = u.uuid
              WHERE p.seller_id = :seller_id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':seller_id', $sellerId, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // Log success
        error_log("Seller products fetched successfully. Seller ID: " . $sellerId);
    } else {
        // Log failure
        error_log("Query failed: " . print_r($stmt->errorInfo(), true));
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$products) {
        return [
            'status' => 'error',
            'message' => 'No products found for the specified seller.'
        ];
    }

    // Fetch additional images for each product
    foreach ($products as &$product) {
        $queryImages = "SELECT image_url FROM product_images WHERE product_id = :product_id";
        $stmtImages = $this->conn->prepare($queryImages);
        $stmtImages->bindParam(':product_id', $product['product_id'], PDO::PARAM_INT);
        $stmtImages->execute();
        $additionalImages = $stmtImages->fetchAll(PDO::FETCH_COLUMN);

        $product['additional_images'] = $additionalImages;
    }

    return [
        'status' => 'success',
        'data' => $products,
        'message' => ''
    ];
}


    // Retrieve products by category
    public function getProductsByCategory($category)
    {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, 
                         p.stock_quantity AS stock, p.category, p.size, p.color, 
                         p.product_image, CONCAT(LEFT(u.first_name, 1), '. ', u.last_name) AS seller_name 
                  FROM product p
                  JOIN users u ON p.seller_id = u.uuid
                  WHERE p.category = :category";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return empty($results)
            ? ['status' => 'success', 'data' => [], 'message' => 'No products available in this category.']
            : ['status' => 'success', 'data' => $results];
    }

    // Retrieve a single product by ID, including its additional images
    public function getProductById($productId)
    {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, p.stock_quantity AS stock, 
                         p.category, p.size, p.color, p.product_image, 
                         GROUP_CONCAT(pi.image_url) AS additional_images 
                  FROM {$this->table} p
                  LEFT JOIN product_images pi ON p.product_id = pi.product_id
                  WHERE p.product_id = :product_id
                  GROUP BY p.product_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Log the result to check if the product was found
        error_log("Product retrieval result: " . print_r($result, true));
    
        return $result;
    }
    

    // Retrieve products by a specific seller's name
    public function getProductsBySellerName($sellerName)
    {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, 
                         p.stock_quantity AS stock, p.category, p.size, p.color, 
                         p.product_image, CONCAT(u.first_name, ' ', u.last_name) AS seller_name
                  FROM product p
                  JOIN users u ON p.seller_id = u.uuid
                  WHERE CONCAT(u.first_name, ' ', u.last_name) = :seller_name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':seller_name', $sellerName, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return empty($results)
            ? ['status' => 'success', 'data' => [], 'message' => 'No products available for this seller.']
            : ['status' => 'success', 'data' => $results];
    }

    // Retrieve all products by a specific seller's UUID
    public function getProductsBySellerID($uuid)
    {
        $query = "SELECT p.product_id, p.product_name, p.description, p.price, 
                         p.stock_quantity AS stock, p.category, p.size, p.color, 
                         p.product_image
                  FROM product p
                  JOIN users u ON p.seller_id = u.uuid
                  WHERE u.uuid = :uuid";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return empty($results)
            ? ['status' => 'success', 'data' => [], 'message' => 'No products available for this seller.']
            : ['status' => 'success', 'data' => $results];
    }

    // Retrieve additional images for a specific product
    public function getProductImages($productId)
    {
        $query = "SELECT image_url FROM product_images WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as a flat array of URLs
    }


    /* ====== SECTION 2: Create Methods ====== */

    // Create a new product (for sellers)
    public function createProduct($data, $sellerId)
    {
        try {
            // Check if the product name already exists for the seller
            $query = "SELECT COUNT(*) FROM " . $this->table . " 
                    WHERE product_name = :product_name AND seller_id = :seller_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':seller_id', $sellerId);
            $stmt->execute();
    
            if ($stmt->fetchColumn() > 0) {
                return json_encode(['status' => 'error', 'message' => 'A product with this name already exists for this seller.']);
            }
    
            // Insert the new product
            $query = "INSERT INTO " . $this->table . " 
                    (product_name, description, price, stock_quantity, category, size, color, product_image, seller_id) 
                    VALUES (:product_name, :description, :price, :stock_quantity, :category, :size, :color, :product_image, :seller_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_name', $data['product_name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':stock_quantity', $data['stock_quantity']);
            $stmt->bindParam(':category', $data['category']);
            $stmt->bindParam(':size', $data['size']);
            $stmt->bindParam(':color', $data['color']);
            $stmt->bindParam(':product_image', $data['product_image']);
            $stmt->bindParam(':seller_id', $sellerId);
    
            if ($stmt->execute()) {
                // Log success
                error_log("Product created successfully. Product Name: " . $data['product_name']);
                return $this->conn->lastInsertId();
            } else {
                // Log failure
                error_log("Query failed: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (\PDOException $e) {
            // Log any exceptions
            error_log("Database error: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
    

    // Add an additional image to the product
    public function addProductImage($productId, $imagePath)
    {
        $query = "INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $imagePath, PDO::PARAM_STR);

        if ($stmt->execute()) {
            error_log("Image added to product_images for product ID $productId: $imagePath");
            return true;
        } else {
            error_log("Failed to add image to product_images: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }


    /* ====== SECTION 3: Update Methods ====== */

    // Update an existing product
    public function updateProduct($productId, $data, $sellerId)
    {
        try {
            // Fetch current product details to compare
            $currentProductQuery = "SELECT product_name FROM " . $this->table . " WHERE product_id = :product_id AND seller_id = :seller_id";
            $currentStmt = $this->conn->prepare($currentProductQuery);
            $currentStmt->bindParam(':product_id', $productId);
            $currentStmt->bindParam(':seller_id', $sellerId);
            $currentStmt->execute();
            $currentProduct = $currentStmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$currentProduct) {
                return json_encode(['status' => 'error', 'message' => 'Product not found']);
            }
    
            // Check for duplicate name if product name is changing
            if ($currentProduct['product_name'] !== $data['product_name']) {
                $query = "SELECT COUNT(*) FROM " . $this->table . " 
                        WHERE product_name = :product_name AND seller_id = :seller_id AND product_id != :product_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':product_name', $data['product_name']);
                $stmt->bindParam(':seller_id', $sellerId);
                $stmt->bindParam(':product_id', $productId);
                $stmt->execute();
    
                if ($stmt->fetchColumn() > 0) {
                    return json_encode(['status' => 'error', 'message' => 'A product with this name already exists for this seller.']);
                }
            }
    
            // Update product details
            $updateQuery = "UPDATE " . $this->table . " 
                            SET product_name = :product_name, description = :description, price = :price, 
                                stock_quantity = :stock_quantity, category = :category, 
                                size = :size, color = :color 
                            WHERE product_id = :product_id AND seller_id = :seller_id";
    
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':product_name', $data['product_name']);
            $updateStmt->bindParam(':description', $data['description']);
            $updateStmt->bindParam(':price', $data['price']);
            $updateStmt->bindParam(':stock_quantity', $data['stock_quantity']);
            $updateStmt->bindParam(':category', $data['category']);
            $updateStmt->bindParam(':size', $data['size']);
            $updateStmt->bindParam(':color', $data['color']);
            $updateStmt->bindParam(':product_id', $productId);
            $updateStmt->bindParam(':seller_id', $sellerId);
    
            if ($updateStmt->execute()) {
                // Log success
                error_log("Product updated successfully. Product ID: " . $productId);
                $affectedRows = $updateStmt->rowCount();
                return $affectedRows > 0
                    ? json_encode(['status' => 'success', 'message' => 'Product details updated successfully'])
                    : json_encode(['status' => 'error', 'message' => 'No changes were made to the product details']);
            } else {
                // Log failure
                error_log("Query failed: " . print_r($updateStmt->errorInfo(), true));
                return json_encode(['status' => 'error', 'message' => 'Failed to update product details']);
            }
        } catch (\PDOException $e) {
            // Log any exceptions
            error_log("Database error: " . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
    
    

    public function updatePrimaryImage($productId, $imagePath)
    {
        $query = "UPDATE product SET product_image = :product_image WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_image', $imagePath, PDO::PARAM_STR);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $affectedRows = $stmt->rowCount();
            error_log("Primary image update - rows affected: $affectedRows");
            return $affectedRows > 0;
        } else {
            error_log("Primary image update failed: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    }

    public function updateProductImage($productId, $newImagePath)
    {
        error_log("Attempting to update image for product ID: $productId with path: $newImagePath");

        $query = "UPDATE product_images SET image_url = :image_url WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_url', $newImagePath, PDO::PARAM_STR);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $affectedRows = $stmt->rowCount();
            error_log("Image update executed. Rows affected: $affectedRows");

            if ($affectedRows > 0) {
                return json_encode(['status' => 'success', 'message' => 'Product image updated successfully']);
            } else {
                error_log("No rows affected in image update; product may not exist or image path is identical.");
                return json_encode(['status' => 'error', 'message' => 'No changes made to the product image']);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("Image update failed: " . print_r($errorInfo, true));
            return json_encode(['status' => 'error', 'message' => 'Failed to update product image']);
        }
    }



    // Check for a duplicate product name
    public function checkDuplicateName($productName, $sellerId, $productId)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " 
                WHERE product_name = :product_name AND seller_id = :seller_id AND product_id != :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_name', $productName);
        $stmt->bindParam(':seller_id', $sellerId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }


    /* ====== SECTION 4: Delete Methods ====== */

    // Delete a product
    public function deleteProduct($productId, $sellerId)
    {
        $query = "DELETE FROM " . $this->table . " WHERE product_id = :product_id AND seller_id = :seller_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':seller_id', $sellerId, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Delete all images associated with a product
    public function deleteProductImages($productId)
    {
        $query = "DELETE FROM product_images WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }


    /* ====== SECTION 5: Search and Filtering Methods ====== */

//  REMOVED SECTION

    /* ====== SECTION 6: Sort Helper Method ====== */

    // Helper method for sorting in advanced search
    private function getSortColumn($sortBy)
    {
        switch ($sortBy) {
            case 'price_asc':
                return 'price ASC';
            case 'price_desc':
                return 'price DESC';
            case 'popularity':
                return 'popularity DESC'; // Assumes a 'popularity' column exists
            default:
                return 'product_name ASC';
        }
    }
}
