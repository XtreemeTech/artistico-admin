<?php

include 'components/connect.php';


$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
  <?php include 'components/stylesheets.php'; ?>
  <!-- Required meta tags -->
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include 'components/header.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <?php include 'components/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              Dashboard
            </h3>
          </div>
          <div class="row grid-margin">
            <div class="col-12">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="statistics-item">
                      <?php
                      $select_users = $conn->prepare("SELECT * FROM `users`");
                      $select_users->execute();
                      $number_of_users = $select_users->rowCount()
                      ?>
                      <p>
                        <i class="icon-sm fa fa-user mr-2"></i>
                        Subscribers
                      </p>
                      <h2><?= $number_of_users; ?></h2>
                      <!-- <label class="badge badge-outline-success badge-pill">2.7% increase</label> -->
                    </div>
                    <div class="statistics-item">
                      <?php
                      $total_completes = 0;
                      $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                      $select_completes->execute(['completed']);
                      if ($select_completes->rowCount() > 0) {
                        while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                          $total_completes += $fetch_completes['total_price'];
                        }
                      }
                      ?>
                      <p>
                        <i class="icon-sm fas fa-chart-line mr-2"></i>
                        Sales (USD)
                      </p>
                      <h2><?= $total_completes; ?></h2>
                    </div>
                    <div class="statistics-item">
                      <?php
                      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                      $select_orders->execute(['pending']);
                      $number_of_orders = $select_orders->rowCount()
                      ?>
                      <p>
                        <i class="icon-sm fas fa-check-circle mr-2"></i>
                        Pending Orders
                      </p>
                      <h2><?= $number_of_orders; ?></h2>
                    </div>
                    <div class="statistics-item">
                      <?php
                      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                      $select_orders->execute(['completed']);
                      $number_of_orders = $select_orders->rowCount()
                      ?>
                      <p>
                        <i class="icon-sm fas fa-check-circle mr-2"></i>
                        Completed Orders
                      </p>
                      <h2><?= $number_of_orders; ?></h2>
                    </div>
                    <div class="statistics-item">
                      <?php
                      $select_admins = $conn->prepare("SELECT * FROM `admins`");
                      $select_admins->execute();
                      $number_of_admins = $select_admins->rowCount()
                      ?>
                      <p>
                        <i class="icon-sm fas fa-user-shield mr-2"></i>
                        Admin accounts
                      </p>
                      <h2><?= $number_of_admins; ?></h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="fas fa-gift"></i>
                    Orders
                  </h4>
                  <canvas id="orders-chart"></canvas>
                  <div id="orders-chart-legend" class="orders-chart-legend"></div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Sales
                  </h4>
                  <canvas id="sales-chart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'components/footer.php'; ?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <?php include 'components/scripts.php'; ?>

  <!-- Custom js for this page-->
  <script src="components/assets/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>
