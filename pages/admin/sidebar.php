<?php 
$currentPage = basename($_SERVER['PHP_SELF']); // gets current file name
?>

<!-- admin/sidebar.php -->
<div class="sidebar">
    <a href="index.php?page=dashboard&area=admin" class="sidebar-item <?php echo ($currentPage=='dashboard.php') ? 'active' : ''; ?>">
        <span class="icon">🏠</span>
        <span class="text">Dashboard</span>
    </a>
    <a href="index.php?page=contactform&area=admin" class="sidebar-item <?php echo ($currentPage=='contactform.php') ? 'active' : ''; ?>">
        <span class="icon">🏠</span>
        <span class="text">Contact Form</span>
    </a>
    <a href="index.php?page=users&area=admin" class="sidebar-item <?php echo ($currentPage=='users.php') ? 'active' : ''; ?>">
        <span class="icon">🏠</span>
        <span class="text">Users</span>
    </a>
    <a href="index.php?page=roles&area=admin" class="sidebar-item <?php echo ($currentPage=='roles.php') ? 'active' : ''; ?>">
        <span class="icon">🏠</span>
        <span class="text">Roles</span>
    </a>
</div>
