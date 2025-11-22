<?php

class CareersService
{
    public static function getCareersData()
    {
        if (!defined('APP_INIT')) {
            http_response_code(403);
            exit("Access denied");
        }

        $pdo = $GLOBALS['pdo'];

        $currentPage = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $conditions = [];
        $params = [];

        // Dynamic Filters
        if (!empty($_GET['job'])) {
            $conditions[] = "title = :job";
            $params[':job'] = $_GET['job'];
        }
        if (!empty($_GET['industry'])) {
            $conditions[] = "industry = :industry";
            $params[':industry'] = $_GET['industry'];
        }
        if (!empty($_GET['location'])) {
            $conditions[] = "location = :location";
            $params[':location'] = $_GET['location'];
        }
        if (!empty($_GET['category'])) {
            $conditions[] = "category = :category";
            $params[':category'] = $_GET['category'];
        }
        if (!empty($_GET['employment'])) {
            $conditions[] = "employment_type = :employment";
            $params[':employment'] = $_GET['employment'];
        }
        if (!empty($_GET['workmodel'])) {
            $conditions[] = "work_model = :workmodel";
            $params[':workmodel'] = $_GET['workmodel'];
        }

        $whereSQL = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Fetch jobs for this page
        $sql = "SELECT * FROM jobs $whereSQL ORDER BY posted_date DESC LIMIT :limit OFFSET :offset";
        $result = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $result->bindValue($key, $value);
        }
        $result->bindValue(':limit', $limit, PDO::PARAM_INT);
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->execute();

        // Total count
        $total_sql = "SELECT COUNT(*) as total FROM jobs $whereSQL";
        $total_result = $pdo->prepare($total_sql);

        foreach ($params as $key => $value) {
            $total_result->bindValue($key, $value);
        }
        $total_result->execute();
        $total_jobs = $total_result->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_jobs / $limit);

        // Determine start and end
        $jobsInPage = $result->rowCount();
        $start = $jobsInPage > 0 ? $offset + 1 : 0;
        $end   = $jobsInPage > 0 ? $offset + $jobsInPage : 0;

        // Helper: distinct dropdowns
        $jobs       = self::getDistinct('title');
        $industries = self::getDistinct('industry');
        $locations  = self::getDistinct('location');
        $categories = self::getDistinct('category');
        $employmentTypes = self::getDistinct('employment_type');
        $workModels = self::getDistinct('work_model');

        // Build filter query for pagination
        $filterQuery = '';
        if (!empty($_GET)) {
            $temp = $_GET;
            unset($temp['pg']);
            $filterQuery = http_build_query($temp) . '&';
        }

        return [
            'result' => $result,
            'total_jobs' => $total_jobs,
            'total_pages' => $total_pages,
            'start' => $start,
            'end' => $end,
            'jobs' => $jobs,
            'industries' => $industries,
            'locations' => $locations,
            'categories' => $categories,
            'employmentTypes' => $employmentTypes,
            'workModels' => $workModels,
            'filterQuery' => $filterQuery,
        ];
    }

    private static function getDistinct($column)
    {
        $pdo = $GLOBALS['pdo'];
        $stmt = $pdo->prepare("SELECT DISTINCT $column FROM jobs ORDER BY $column ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
