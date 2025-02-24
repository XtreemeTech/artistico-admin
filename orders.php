<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

if (isset($_POST['update_payment'])) {
  $order_id = $_POST['order_id'];
  $payment_status = $_POST['payment_status'];
  $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
  $update_payment->execute([$payment_status, $order_id]);
  $message[] = 'payment status updated!';
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
  $delete_order->execute([$delete_id]);
  header('location:orders.php');
}

if (isset($_POST['save_comment'])) {
  $user_id = $_POST['user_id']; // Ensure you pass the user_id through the form or another secure way
  $comment = $_POST['comment'];

  // Prepare the SQL statement to update the comment for the specific order
  $comment_order = $conn->prepare("UPDATE `orders` SET comments = ? WHERE id = ?");
  $comment_order->execute([$comment, $user_id]);

  // Redirect to orders.php
  header('Location: orders.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders</title>
  <link rel="stylesheet" href="components/assets/css/order.css">
  <?php include 'components/stylesheets.php'; ?>
</head>

<body>
  <?php include 'components/header.php'; ?>
  <div class="container-fluid page-body-wrapper">
    <?php include 'components/sidebar.php'; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Orders</h4>
            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table id="order-listing" class="table">
                    <thead>
                      <tr>
                        <th>Order ID</th>
                        <th>Purchased On</th>
                        <th>Customer</th>
                        <th>Ship to</th>
                        <th>URL</th>
                        <th>Dimensions</th>
                        <th>Format</th>
                        <th>Medium</th>
                        <th>Comments</th>
                        <th>Total</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $select_orders = $conn->prepare("SELECT * FROM `orders`");
                      $select_orders->execute();
                      if ($select_orders->rowCount() > 0) {
                        $i = 0;
                        $order_id = 1;
                        $prev = '';
                        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                          $i++;
                          $order_items = $conn->prepare("SELECT * FROM `orderitems` WHERE order_id = ?");
                          $order_items->execute([$fetch_orders["id"]]);
                          while ($fetch_items = $order_items->fetch(PDO::FETCH_ASSOC)) {
                      ?>
                            <tr>
                              <td><?= $order_id; ?></td>
                              <td><?= $fetch_orders['placed_on'] == $prev ? "---" : $fetch_orders['placed_on']; ?></td>
                              <td><?= $fetch_orders['name']; ?></td>
                              <td><?= $fetch_orders['address']; ?></td>
                              <td><a href="<?= $fetch_items['image_url']; ?>">Image</a></td>
                              <td><?= $fetch_items['image_size']; ?></td>
                              <td><?= $fetch_items['image_format']; ?></td>
                              <td><?= $fetch_items['image_medium']; ?></td>
                              <td><?= $fetch_orders['comments'] ?></td>
                              <td>$<?= $fetch_orders['total_price']; ?></td>
                              <td>
                                <!-- <button class="badge badge-< ?= $fetch_orders['payment_status'] == 'completed' ?  'success' : 'info' ?>">< ?= $fetch_orders['payment_status']; ?></button> -->
                                <form action="" method="post" class="action">
                                  <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                                  <select class="select badge" name="payment_status">
                                    <option selected><?= $fetch_orders['payment_status']; ?></option>
                                    <option
                                      value="<?= $fetch_orders['payment_status'] == 'completed' ? 'pending' : 'completed'; ?>">
                                      <?= $fetch_orders['payment_status'] == 'completed' ? 'pending' : 'completed'; ?>
                                    </option>
                                  </select>
                                  <div class="actions">
                                    <a href="orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn delete"
                                      onclick="return confirm('This action is irreversible. Do you want to delete this order?');">
                                      <i class="fa fa-trash"></i>
                                    </a>
                                    <button class="comment" type="button"
                                      data-target="#commentContainer<?= $fetch_orders['id']; ?>">
                                      <i class="fa fa-comment"></i>
                                    </button>
                                    <button type="submit" value="update" class="option-btn update" name="update_payment">
                                      <i class="fa fa-check"></i>
                                    </button>
                                  </div>
                                </form>
                                <div class="commentContainer" id="commentContainer<?= $fetch_orders['id']; ?>">
                                  <form action="" method="post" class="action">
                                    <input type="hidden" name="user_id" value="<?= $fetch_orders['id']; ?>">
                                    <input type="text" placeholder="Enter comment" name="comment">
                                    <button class="send" type="submit" name="save_comment">
                                      <i class="fa fa-paper-plane"></i>
                                    </button>
                                  </form>
                                </div>
                              </td>
                            </tr>
                      <?php
                          }
                          if ($fetch_orders['placed_on'] != $prev) {
                            $order_id++;
                          }
                          $prev = $fetch_orders['placed_on'];
                        }
                      } else {
                        echo '<p class="empty">no orders placed yet!</p>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'components/footer.php'; ?>
    </div>
  </div>
  </div>
  <?php include 'components/scripts.php'; ?>
  <script src="components/assets/js/data-table.js"></script>
  <script>
    function toggleComment(button) {
      // Get the target comment container ID from the button's data attribute
      var targetSelector = button.getAttribute('data-target');
      var container = document.querySelector(targetSelector);

      // Toggle the display style of the comment container
      if (container) {
        container.style.display = container.style.display === 'block' ? 'none' : 'block';
      }
    }

    // Attach event listener to all comment buttons
    document.querySelectorAll('.comment').forEach(function(button) {
      button.addEventListener('click', function() {
        toggleComment(this);
      });
    });
  </script>



</body>

</html>
