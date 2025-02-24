<?php

include 'components/connect.php';



$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

if (isset($_GET['delete_user'])) {
  $delete_id = $_GET['delete_user'];
  $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
  $delete_user->execute([$delete_id]);
  $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
  $delete_orders->execute([$delete_id]);
  $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
  $delete_messages->execute([$delete_id]);
  $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
  $delete_cart->execute([$delete_id]);
  $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
  $delete_wishlist->execute([$delete_id]);
  header('location:users.php');
}


if (isset($_GET['delete_admin'])) {
  $delete_id = $_GET['delete_admin'];
  $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
  $delete_admins->execute([$delete_id]);
  header('location:users.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users</title>
  <?php include 'components/stylesheets.php'; ?>
  <style>
    tr:has(td a.option-btn) {
      background-color: #fff3f3 !important;
    }
  </style>
</head>

<body>
  <?php include 'components/header.php'; ?>
  <div class="container-fluid page-body-wrapper">
    <?php include 'components/sidebar.php'; ?>
    <div class="main-panel">
      <div class="content-wrapper">

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">User accounts</h4>
            <!-- <p class="page-description">Add class <code>.table-striped</code> for table</p> -->
            <div class="row">
              <div class="table-sorter-wrapper col-lg-12 table-responsive" style="height: 100vh; overflow: auto; ">
                <table id="sortable-table-2" class="table .sortable-table table-striped" style="position: relative;">
                  <thead style=" background: white; position: sticky; top: 0;">
                    <tr>
                      <th class="sortStyle">ID<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Name<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Phone<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Email<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Role<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Action<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Last Login<i class="fa fa-angle-down"></i></th>
                      <th class="sortStyle">Registered On<i class="fa fa-angle-down"></i></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Prepare and execute the combined SQL query
                    $select_accounts = $conn->prepare("
                      SELECT id, name, NULL AS phone, NULL AS email, NULL AS last_login, NULL AS registered_on, 'admin' AS type FROM admins
                      UNION
                      SELECT u.id, u.name, MAX(o.number) AS phone, u.email, u.last_login, u.registered_on, 'subscriber' AS type FROM users u
                      LEFT JOIN orders o ON u.id = o.user_id
                      GROUP BY u.id, u.name, u.email
                      ORDER BY id ASC;
                    ");
                    $select_accounts->execute();

                    // Check if there are any records
                    if ($select_accounts->rowCount() > 0) {
                      $i = 1;
                      while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                        // Determine if the record is an admin or a user
                        $sr = $i;
                        $id = $fetch_accounts['id'];
                        $type = $fetch_accounts['type'];
                        $name = $fetch_accounts['name'];
                        $last_login = $fetch_accounts['last_login'];
                        $registered_on = $fetch_accounts['registered_on'];
                        $i++;
                        if ($type == 'admin') {
                          $phone = '';
                          $email = '';
                          $type = ($id == $admin_id) ? "You (Admin)" : $type;
                          $action = ($id == $admin_id) ? '<a href="update_profile.php" class="option-btn">Update</a>' : '';
                        } else {
                          $phone = $fetch_accounts['phone'];
                          $email = $fetch_accounts['email'];
                          $action = '<a href="users.php?delete_user=' . $id . '" onclick="return confirm(\'All the user related information will also be deleted. Delete this account?\')" class="delete-btn text-danger">Delete</a>';
                        }
                    ?>
                        <tr>
                          <td><?= $sr; ?></td>
                          <td><?= $name; ?></td>
                          <td><?= $phone; ?></td>
                          <td><?= $email; ?></td>
                          <td><?= ucfirst($type); ?></td>
                          <td><?= $action; ?></td>
                          <td><?= $last_login; ?></td>
                          <td><?= $registered_on; ?></td>
                        </tr>
                    <?php
                      }
                    } else {
                      echo '<p class="empty">no accounts available!</p>';
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'components/footer.php'; ?>
    </div>
  </div>
  <?php include 'components/scripts.php'; ?>
  <script src="assets/js/tablesorter.js"></script>
  <script src="assets/js/jq.tablesort.js"></script>

</body>

</html>
