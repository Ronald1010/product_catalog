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
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can create products.']);
        }

        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        // Handle primary image
        if (isset($files['primary_image']) && $files['primary_image']['error'] === UPLOAD_ERR_OK) {
            $primaryImagePath = $this->uploadImage($files['primary_image']);
            $data['product_image'] = $primaryImagePath;
        } else {
            error_log("Primary image upload failed or not provided.");
        }

        $productId = $this->product->createProduct($data, $sellerId);

        if ($productId) {
            if (isset($files['additional_images'])) {
                $this->handleAdditionalImages($files['additional_images'], $productId);
            }
            return json_encode(['status' => 'success', 'message' => 'Product created successfully']);
        }

        return json_encode(['status' => 'error', 'message' => 'Failed to create product']);
    }

    /* ====== SECTION 2: Product Update ====== */

    // Update only the textual data of a product
    public function updateProductDetails($productId, $data, $token)
    {
        if (!$this->isSeller($token)) {
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can update products.']);
        }

        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        $existingProduct = $this->product->getProductById($productId);
        if (!$existingProduct) {
            return json_encode(['status' => 'error', 'message' => 'Product not found']);
        }

        if (!isset($data['product_image'])) {
            $data['product_image'] = $existingProduct['product_image'];
        }

        if ($existingProduct['product_name'] !== $data['product_name']) {
            $duplicateCheck = $this->product->checkDuplicateName($data['product_name'], $sellerId, $productId);
            if ($duplicateCheck) {
                return json_encode(['status' => 'error', 'message' => 'A product with this name already exists for this seller.']);
            }
        }

        $result = $this->product->updateProduct($productId, $data, $sellerId);

        return $result
            ? json_encode(['status' => 'success', 'message' => 'Product details updated successfully'])
            : json_encode(['status' => 'error', 'message' => 'Failed to update product details']);
    }

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
        if (!$this->isSeller($token)) {
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can view their products.']);
        }

        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        $products = $this->product->getProductsBySellerId($sellerId);

        if (empty($products)) {
            return json_encode(['status' => 'success', 'data' => [], 'message' => 'No products found for this seller']);
        }

        return json_encode(['status' => 'success', 'data' => $products]);
    }

    /* ====== SECTION 4: Product Deletion ====== */

    // Delete a product
    public function deleteProduct($productId, $token)
    {
        if (!$this->isSeller($token)) {
            return json_encode(['status' => 'error', 'message' => 'Unauthorized. Only sellers can delete products.']);
        }

        $decoded = $this->tokenValidator->validateToken($token);
        $sellerId = $decoded->uuid;

        $imagePaths = $this->product->getProductImages($productId);

        foreach ($imagePaths as $path) {
            $filePath = "public/images/" . basename($path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->product->deleteProductImages($productId);
        $result = $this->product->deleteProduct($productId, $sellerId);

        return $result
            ? json_encode(['status' => 'success', 'message' => 'Product and associated images deleted successfully'])
            : json_encode(['status' => 'error', 'message' => 'Failed to delete product or product not found']);
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
