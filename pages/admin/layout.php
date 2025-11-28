<!DOCTYPE html>
<html>

<head>
  <title>CSOFT.com</title>

  <!-- Correct absolute asset paths -->
  <link rel="stylesheet" href="<?= $BASE ?>/assets/style.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>

  <?php include __DIR__ . '/header.php'; ?>

  <div class="content-wrapper">
    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
      <?php
      if (!empty($view) && file_exists($view)) {
        include $view;
      } else {
        echo "<!-- No view selected or file not found -->";
      }
      ?>
    </main>
  </div>

  <?php include __DIR__ . '/footer.php'; ?>

  <!-- Correct JS path -->
  <script src="<?= $BASE ?>/assets/script.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



  <script>
    $(document).ready(function() {
      $('.table-container table').DataTable({
        paging: true,
        searching: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        info: true,
        ordering: true
      });
    });
  </script>

  <script>
    toastr.options = {
      "closeButton": true,
      "positionClass": "toast-top-right",
      "timeOut": "2500"
    };
  </script>

</body>

</html>