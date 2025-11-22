<?php

class ProductsService
{
    public static function getProductsData()
    {
        $pdo = $GLOBALS['pdo'];

        $currentPage = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
        $limit = 12;
        $offset = ($currentPage - 1) * $limit;

        $conditions = [];
        $params = [];

        // Filters
        if (!empty($_GET['category'])) {
            $conditions[] = "category = :category";
            $params[':category'] = $_GET['category'];
        }
        if (!empty($_GET['brand'])) {
            $conditions[] = "brand = :brand";
            $params[':brand'] = $_GET['brand'];
        }
        if (!empty($_GET['price_min'])) {
            $conditions[] = "price >= :price_min";
            $params[':price_min'] = $_GET['price_min'];
        }
        if (!empty($_GET['price_max'])) {
            $conditions[] = "price <= :price_max";
            $params[':price_max'] = $_GET['price_max'];
        }

        $whereSQL = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

        // Fetch products
        $sql = "SELECT * FROM products $whereSQL ORDER BY name ASC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Count
        $total_sql = "SELECT COUNT(*) AS total FROM products $whereSQL";
        $total_stmt = $pdo->prepare($total_sql);

        foreach ($params as $key => $value) {
            $total_stmt->bindValue($key, $value);
        }

        $total_stmt->execute();
        $total_products = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_products / $limit);

        // Distinct filters
        $categories = self::getDistinct("category");
        $brands = self::getDistinct("brand");

        // Query string builder
        $filterQuery = '';
        if (!empty($_GET)) {
            $temp = $_GET;
            unset($temp['pg']);
            $filterQuery = http_build_query($temp) . '&';
        }


        return [
            "stmt" => $stmt,
            "categories" => $categories,
            "brands" => $brands,
            "currentPage" => $currentPage,
            "total_products" => $total_products,
            "total_pages" => $total_pages,
            "offset" => $offset,
            "filterQuery" => $filterQuery,
            "cart" => $_SESSION['cart'] ?? [],
        ];
    }

    private static function getDistinct($column)
    {
        $pdo = $GLOBALS['pdo'];
        $stmt = $pdo->prepare("SELECT DISTINCT $column FROM products ORDER BY $column ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
