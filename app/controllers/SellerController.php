<?php

namespace App\Controllers;

use App\Models\Product;
use App\Providers\Validation\ValidateTokenProvider;
use PDO;

class SellerController
{
    protected $product;
    protected $tokenValidator;

    public function __construct(PDO $db, ValidateTokenProvider $tokenValidator)
    {
        $this->product = new Product($db);
        $this->tokenValidator = $tokenValidator;
    }

    private function isSeller($token)
    {
        $decoded = $this->tokenValidator->validateToken($token);
        return isset($decoded->role) && $decoded->role === '0002';
    }

    /* ====== SECTION 1: Product Creation ====== */

    // Create a new product
    public function createProduct($data, $files, $token)
    {
        if (!$this->isSeller($token)) {
            // Unauthorized access response
            return json_encode([
                'status' => 'error',
                'message' => 'Unauthorized. Only sellers can create products.'
            ], JSON_PRETTY_PRINT);
        }

        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        // Handle primary image upload
        if (isset($files['primary_image']) && $files['primary_image']['error'] === UPLOAD_ERR_OK) {
            $primaryImagePath = $this->uploadImage($files['primary_image']);
            $data['product_image'] = $primaryImagePath;
        } else {
            error_log("Primary image upload failed or not provided.");
        }

        // Create the product in the database
        $productId = $this->product->createProduct($data, $sellerId);

        if ($productId) {
            // Handle additional images if they exist
            if (isset($files['additional_images'])) {
                $this->handleAdditionalImages($files['additional_images'], $productId);
            }

            // Success response
            return json_encode([
                'status' => 'success',
                'message' => 'Product created successfully.',
                'data' => [
                    'product_id' => $productId,
                    'product_name' => $data['product_name'],
                    'seller_id' => $sellerId,
                    'product_image' => $data['product_image']
                ]
            ], JSON_PRETTY_PRINT);
        }

        // Failure response
        return json_encode([
            'status' => 'error',
            'message' => 'Failed to create product.',
            'data' => null
        ], JSON_PRETTY_PRINT);
    }


    /* ====== SECTION 2: Product Update ====== */

    // Update only the textual data of a product
    public function updateProductDetails($productId, $data, $token)
    {
        // Check if the user is a seller
        if (!$this->isSeller($token)) {
            return $this->generateResponse('error', 'Unauthorized. Only sellers can update products.');
        }
    
        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;
    
        // Get the existing product to check for validation
        $existingProduct = $this->product->getProductById($productId);
        if (!$existingProduct) {
            return $this->generateResponse('error', 'Product not found.');
        }
    
        // If product image is not set in the data, retain the existing one
        if (!isset($data['product_image'])) {
            $data['product_image'] = $existingProduct['product_image'];
        }
    
        // Check for duplicate product name
        if ($existingProduct['product_name'] !== $data['product_name']) {
            $duplicateCheck = $this->product->checkDuplicateName($data['product_name'], $sellerId, $productId);
            if ($duplicateCheck) {
                return $this->generateResponse('error', 'A product with this name already exists for this seller.');
            }
        }
    
        // Update the product in the database
        $result = $this->product->updateProduct($productId, $data, $sellerId);
    
        // Return response based on the result
        if ($result) {
            return $this->generateResponse('success', 'Product details updated successfully.');
        } else {
            return $this->generateResponse('error', 'Failed to update product details.');
        }
    }
    
    /**
     * Helper function to generate consistent responses
     */
    private function generateResponse($status, $message, $data = null)
    {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], JSON_PRETTY_PRINT);
    }
    

    // Update Image
    public function updateProductImages($productId, $files, $token)
    {
        if (!$this->isSeller($token)) {
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can update product images.']);
        }

        error_log("Received Files array in POST: " . print_r($files, true));
        error_log("Initial Files array in POST request: " . print_r($_FILES, true));

        $imageUpdateStatus = false;

        if ($this->product->deleteProductImages($productId)) {
            error_log("Existing images deleted for product ID: $productId");
        } else {
            error_log("Failed to delete existing images for product ID: $productId");
            return json_encode(['status' => 'error', 'message' => 'Failed to update images']);
        }

        if (isset($files['primary_image']) && $files['primary_image']['error'] === UPLOAD_ERR_OK) {
            $primaryImagePath = $this->uploadImage($files['primary_image']);
            if ($primaryImagePath) {
                $result = $this->product->updatePrimaryImage($productId, $primaryImagePath);
                if ($result) {
                    error_log("Primary image updated in database for product ID: $productId");
                    $imageUpdateStatus = true;
                }
            }
        } else {
            error_log("Primary image not provided or has an upload error.");
        }

        if (isset($files['additional_images']) && is_array($files['additional_images']['name'])) {
            foreach ($files['additional_images']['tmp_name'] as $index => $tmpName) {
                if ($files['additional_images']['error'][$index] === UPLOAD_ERR_OK) {
                    $additionalImagePath = $this->uploadImage([
                        'name' => $files['additional_images']['name'][$index],
                        'tmp_name' => $tmpName,
                        'error' => $files['additional_images']['error'][$index]
                    ]);
                    if ($additionalImagePath) {
                        $result = $this->product->addProductImage($productId, $additionalImagePath);
                        if ($result) {
                            error_log("Additional image added in database for product ID: $productId at index: $index");
                            $imageUpdateStatus = true;
                        }
                    }
                }
            }
        } else {
            error_log("No additional images provided or invalid format.");
        }

        return $imageUpdateStatus
            ? json_encode(['status' => 'success', 'message' => 'Product images updated successfully'])
            : json_encode(['status' => 'error', 'message' => 'No images were updated']);
    }

    /* ====== SECTION 3: Product Viewing ====== */

    // View products of the seller
    public function viewOwnProducts($token)
    {
        // Check if the user is a seller
        if (!$this->isSeller($token)) {
            return json_encode([
                'status' => 'error',
                'message' => 'Unauthorized. Only sellers can view their products.',
                'data' => null
            ], JSON_PRETTY_PRINT);
        }

        // Decode the token to get the seller ID
        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        // Retrieve the products associated with the seller
        $products = $this->product->getProductsBySellerId($sellerId);

        // If no products are found, return a message indicating that
        if (empty($products)) {
            return json_encode([
                'status' => 'success',
                'message' => 'No products found for this seller.',
                'data' => []
            ], JSON_PRETTY_PRINT);
        }

        // Return the products in a structured response
        return json_encode([
            'status' => 'success',
            'message' => 'Products retrieved successfully.',
            'data' => $products
        ], JSON_PRETTY_PRINT);
    }

// View Specific Product of Seller
public function viewSpecificProduct($productId, $token)
{
    error_log("Product ID: $productId");

    // Check if the user is a seller
    if (!$this->isSeller($token)) {
        return json_encode([
            'status' => 'error', 
            'message' => 'Unauthorized. Only sellers can view their products.'
        ]);
    }

    // Decode the token to get the seller ID
    $decoded = $this->tokenValidator->validateToken($token);
    if (!$decoded) {
        error_log("Token validation failed.");
        return json_encode([
            'status' => 'error', 
            'message' => 'Token validation failed.'
        ]);
    }

    $sellerId = $decoded->uuid;

    // Fetch the product details
    $product = $this->product->getProductById($productId);

    // Log the fetched product data for debugging
    error_log("Product Retrieved: " . print_r($product, true)); // Log the product data

    // Check if the product exists
    if (!$product) {
        return json_encode([
            'status' => 'error', 
            'message' => 'Product not found'
        ]);
    }

    // Check if the product belongs to the seller
    if ($product['seller_id'] !== $sellerId) {
        return json_encode([
            'status' => 'error', 
            'message' => 'Product does not belong to the seller'
        ]);
    }

    // Return the product data if everything is correct
    return json_encode([
        'status' => 'success', 
        'data' => $product
    ]);
}


    /* ====== SECTION 4: Product Deletion ====== */

    // Delete a product
    public function deleteProduct($productId, $token)
    {
        if (!$this->isSeller($token)) {
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can delete products.']);
        }
    
        // Validate token and get seller ID
        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;
    
        // Check if the product exists
        $product = $this->product->getProductById($productId);
        if (!$product) {
            // If no product is found, return the message "No products found"
            return json_encode(['status' => 'error', 'message' => 'No product found']);
        }
    
        // Get the paths of product images
        $imagePaths = $this->product->getProductImages($productId);
        $deletedFiles = true;  // Flag to track file deletion success
    
        // Loop through each image path and delete the files
        foreach ($imagePaths as $path) {
            $filePath = "public/images/" . basename($path);
    
            // Check if the file exists before attempting deletion
            if (file_exists($filePath)) {
                // Try to delete the file
                if (unlink($filePath)) {
                    // Log successful deletion
                    error_log("File deleted: $filePath");
                } else {
                    // If file deletion fails, set flag to false and log error
                    $deletedFiles = false;
                    error_log("Failed to delete file: $filePath");
                }
            } else {
                // Log that the file was not found
                error_log("File not found: $filePath");
            }
        }
    
        // Proceed to delete the product images from the database
        $this->product->deleteProductImages($productId);
    
        // Delete the product from the database
        $productDeletionResult = $this->product->deleteProduct($productId, $sellerId);
    
        // Check if the deletion process was successful
        if ($productDeletionResult && $deletedFiles) {
            return json_encode(['status' => 'success', 'message' => 'Product and associated images deleted successfully']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Failed to delete product or product images']);
        }
    }
    
    /* ====== SECTION 5: Helper Methods ====== */

    private function uploadImage($file)
    {
        $targetDir = "public/images/";
        $fileName = uniqid() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $targetFilePath;
        } else {
            return null;
        }
    }

    private function handleAdditionalImages($images, $productId)
    {
        foreach ($images['tmp_name'] as $index => $tmpName) {
            if ($images['error'][$index] === UPLOAD_ERR_OK) {
                $imagePath = $this->uploadImage([
                    'name' => $images['name'][$index],
                    'tmp_name' => $tmpName,
                    'error' => $images['error'][$index]
                ]);

                if ($imagePath) {
                    $this->product->addProductImage($productId, $imagePath);
                }
            }
        }
    }
}
