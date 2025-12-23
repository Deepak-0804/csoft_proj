<?php
$data = SidebarService::getSidebarItems();
$departments   = $data['departments'];
$screensByDept = $data['screensByDept'];
$currentScreen = $_GET['page'] ?? '';
?>

<style> 
  .admin-sidebar {
    background: skyblue;
    padding: 15px 0;
    height: 100vh;
    width: 240px;
    border-right: 1px solid #ddd;
}

.nav-link {
    color: #333;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 6px;
}

.nav-link:hover {
    background: #e2e8f0;
}

.nav-link.active {
    background: #4f46e5 !important;
    color: #fff !important;
}

.dept-link .arrow {
    transition: transform .3s ease;
}

.collapse.show ~ .nav-item .arrow,
.dept-link:not(.collapsed) .arrow {
    transform: rotate(90deg);
}

.screen-link {
    border-radius: 6px;
    margin-bottom: 3px;
}

.screen-link:hover {
    background: #e2e8f0;
}

</style>
<div class="container-fluid admin-sidebar">

    <ul class="nav nav-pills flex-column">

        <?php foreach ($departments as $dept): 
            $deptId = $dept['DeptId'];
            $deptOpen = false;

            // Check if this department contains active screen
            if (!empty($screensByDept[$deptId])) {
                foreach ($screensByDept[$deptId] as $scr) {
                    if ($scr['ControllerName'] === $currentScreen) {
                        $deptOpen = true;
                        break;
                    }
                }
            }
        ?>

            <!-- Department Item -->
            <li class="nav-item">
                <a class="nav-link dept-link <?= $deptOpen ? '' : 'collapsed' ?>" 
                    data-bs-toggle="collapse"
                    href="#dept-<?= $deptId ?>" 
                    role="button">
                    
                    <i class="<?= $dept['CssClass'] ?> me-2"></i>
                    <?= htmlspecialchars($dept['DeptDisplayName']) ?>

                </a>
            </li>

            <!-- Screens under this Department -->
            <div class="collapse <?= $deptOpen ? 'show' : '' ?>" id="dept-<?= $deptId ?>">

                <?php foreach ($screensByDept[$deptId] ?? [] as $scr): ?>
                    <li class="nav-item ms-4">

                        <a class="nav-link screen-link <?= ($scr['ControllerName'] === $currentScreen) ? 'active' : '' ?>"
                            href="index.php?page=<?= $scr['ControllerName'] ?>&area=admin">

                            <i class="<?= $scr['CssClass'] ?> me-2"></i>
                            <?= htmlspecialchars($scr['ScreenDisplayName']) ?>
                        </a>

                    </li>
                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>

    </ul>
</div>
