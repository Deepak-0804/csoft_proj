<?php
if (!defined('APP_INIT')) {
  http_response_code(403);
  exit("Access denied");
}

require_once __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';

$cartCount = 0;

if (isset($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['quantity'];
  }
}
?>
<style>
  header {
    position: sticky;
    top: 0;
    z-index: 1000;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    border-bottom: 1px solid black;
  }

  nav a {
    margin: 0 15px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
  }

  .navbar-brand {
    background-color: aliceblue;
  }

  .navbar-dark {
    --bs-navbar-color: azure;
  }
</style>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4 py-3">

      <!-- Logo -->
      <a class="navbar-brand" href="index.php?page=home">
        <img src="../assets/images/Csoft_Logo_3_x_2 Trans.png" alt="Company Logo" height="40">
      </a>

      <!-- Hamburger Toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Collapsible Menu -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=about">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=contact">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=careers">Careers</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=products">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=cart">
              <i class="bi bi-cart"></i>
              <span class="badge bg-danger">
                <?php echo $cartCount; ?>
              </span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>