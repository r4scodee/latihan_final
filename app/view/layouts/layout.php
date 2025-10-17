<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? "Tani Digital" ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard-style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css"/>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

</head>

<body>

  <div class="main-content min-vh-100">
    <?= $content ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/dashboard-style.js"></script>
  <script src="<?= BASE_URL ?>assets/js/chatbot.js"></script>
  <script src="<?= BASE_URL ?>assets/js/apexcharts.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/dashboard-init.min.js"></script>
  <script src="<?= BASE_URL ?>assets/js/theme-customizer-init.min.js"></script>
</body>

</html>