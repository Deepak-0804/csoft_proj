<?php

class SidebarService {

    public static function getSidebarItems() {
        $pdo = $GLOBALS['pdo'];

        // Fetch departments
        $departments = $pdo->query("
            SELECT DeptId, DeptDisplayName, CssClass 
            FROM masdepartment
            WHERE IsActive = 1
            ORDER BY DisplayOrder
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch screens
        $screens = $pdo->query("
            SELECT ScreenId, ScreenDisplayName, ControllerName, DeptId, CssClass
            FROM screen
            WHERE IsActive = 1
            ORDER BY DisplayOrder
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Format screens grouped by department
        $screensByDept = [];
        foreach ($screens as $scr) {
            $screensByDept[$scr['DeptId']][] = $scr;
        }

        return [
            'departments' => $departments,
            'screensByDept' => $screensByDept
        ];
    }
}
