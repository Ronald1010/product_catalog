<?php

namespace App\Controllers;

use App\Models\Search;
use PDO;

class SearchController
{
    private $searchModel;

    public function __construct(PDO $db)
    {
        $this->searchModel = new Search($db);
    }

    /**
     * Search for products by name.
     *
     * @param string $keyword - The keyword to search for in product names.
     * @return string - JSON response containing search results or an error message.
     */
    public function searchByName($keyword)
    {
        if (empty($keyword)) {
            return json_encode([
                'status' => 'error',
                'message' => 'Keyword is required for search.'
            ]);
        }

        // Fetch results from the model
        $results = $this->searchModel->getSearchResultsByName($keyword);

        // Return response
        if (!empty($results)) {
            return json_encode([
                'status' => 'success',
                'data' => $results
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'No products found with the given keyword.'
            ]);
        }
    }
}
?>
