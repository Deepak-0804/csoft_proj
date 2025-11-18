
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CSOFT.com</title>

  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons & other styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">

<!-- Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- jQuery (must be before Toastr) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script src="https://www.paypal.com/sdk/js?client-id=AUAfFwEXG5AQqaJEhiMyeJcUX_ScyP2upO_6gwp-6TU9rL75wNMf7fXDQStZbJ_-cxsb1ULzRivUGh6D&currency=USD"></script>

</head>

<body>
  <?php include __DIR__ . '/../pages/header.php'; ?>
  <main>
    <?php if (!empty($view) && file_exists($view)) {
      include $view;
    } else {
      echo "<!-- No view selected or file not found -->";
    } ?> <!-- child view comes here -->
  </main>

  <?php include __DIR__ . '/../pages/footer.php'; ?>

    <script src="assets/script.js"></script>  <!-- ADD HERE -->


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Load Google's script (usually at end of form or page) -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>


<script>
toastr.options = {
  "closeButton": true,
  "positionClass": "toast-top-right",
  "timeOut": "2500"
};
</script>

</body>

</html>