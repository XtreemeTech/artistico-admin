<?php

include 'components/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

$user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($user_id <= 0) {
  header('location:users.php');
  exit();
}

$select_user = $conn->prepare("SELECT id, name, email FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user = $select_user->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  header('location:users.php');
  exit();
}

// Saved / collection images — what the user kept from their own generations.
$select_collection = $conn->prepare("SELECT id, title, image_url, created_at FROM `collections` WHERE user_id = ? ORDER BY created_at DESC");
$select_collection->execute([$user_id]);
$collection_images = $select_collection->fetchAll(PDO::FETCH_ASSOC);

// Purchased images — same image_url the customer had in their cart, carried through to the order.
$select_purchased = $conn->prepare("
  SELECT oi.image_url, oi.image_title, oi.image_size, oi.image_format, oi.image_medium, oi.price,
         o.id AS order_id, o.placed_on, o.payment_status
  FROM `orderitems` oi
  JOIN `orders` o ON oi.order_id = o.id
  WHERE o.user_id = ?
  ORDER BY o.placed_on DESC
");
$select_purchased->execute([$user_id]);
$purchased_images = $select_purchased->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Images</title>
  <?php include 'components/stylesheets.php'; ?>
  <style>
    .image-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .image-grid .image-card {
      width: 160px;
      text-align: center;
    }

    .image-grid .image-card img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #e0e0e0;
      display: block;
    }

    .image-grid .image-card .meta {
      font-size: 12px;
      color: #6c757d;
      margin-top: 4px;
      word-break: break-word;
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
            <h4 class="card-title">
              Images for <?= htmlspecialchars($user['name']); ?>
              <small class="text-muted">(<?= htmlspecialchars($user['email']); ?>)</small>
            </h4>
            <a href="users.php" class="option-btn">&larr; Back to users</a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Saved collection (<?= count($collection_images); ?>)</h4>
            <?php if (count($collection_images) === 0): ?>
              <p class="empty">no saved images yet!</p>
            <?php else: ?>
              <div class="image-grid">
                <?php foreach ($collection_images as $img): ?>
                  <div class="image-card">
                    <a href="<?= htmlspecialchars($img['image_url']); ?>" target="_blank">
                      <img src="<?= htmlspecialchars($img['image_url']); ?>" alt="<?= htmlspecialchars($img['title']); ?>" loading="lazy">
                    </a>
                    <div class="meta">
                      <?= htmlspecialchars($img['title']); ?><br>
                      <?= htmlspecialchars($img['created_at']); ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Purchased (<?= count($purchased_images); ?>)</h4>
            <?php if (count($purchased_images) === 0): ?>
              <p class="empty">no purchases yet!</p>
            <?php else: ?>
              <div class="image-grid">
                <?php foreach ($purchased_images as $img): ?>
                  <div class="image-card">
                    <a href="<?= htmlspecialchars($img['image_url']); ?>" target="_blank">
                      <img src="<?= htmlspecialchars($img['image_url']); ?>" alt="<?= htmlspecialchars($img['image_title']); ?>" loading="lazy">
                    </a>
                    <div class="meta">
                      <?= htmlspecialchars($img['image_title']); ?><br>
                      <?= htmlspecialchars($img['image_size']); ?> &middot; <?= htmlspecialchars($img['image_format']); ?> &middot; <?= htmlspecialchars($img['image_medium']); ?><br>
                      $<?= htmlspecialchars($img['price']); ?> &middot; <?= htmlspecialchars($img['payment_status']); ?><br>
                      <a href="orders.php">order #<?= (int) $img['order_id']; ?></a> &middot; <?= htmlspecialchars($img['placed_on']); ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>
      <?php include 'components/footer.php'; ?>
    </div>
  </div>
  <?php include 'components/scripts.php'; ?>
</body>

</html>
