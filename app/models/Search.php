<?php

namespace App\Models;

use PDO;

class Search
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Search for products by name.
     *
     * @param string $keyword - The search keyword for the product name.
     * @return array - Array of matching products or an empty array if no results.
     */
    public function getSearchResultsByName($keyword)
    {
        // Prepare SQL query to search products by name using LIKE operator
        $sql = "SELECT * FROM products WHERE product_name LIKE :keyword";
        $stmt = $this->db->prepare($sql);

        // Bind parameters with wildcard search
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);

        // Execute query
        $stmt->execute();

        // Fetch results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
