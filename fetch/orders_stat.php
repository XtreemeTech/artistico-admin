<?php
include '../components/connect.php';

try {
  // Prepare the SQL query
  $query = $conn->prepare("
        SELECT
            DATE_FORMAT(placed_on, '%Y-%m') AS order_month,
            COUNT(*) AS total_orders,
            SUM(CASE WHEN payment_status = 'completed' THEN 1 ELSE 0 END) AS completed_orders,
            SUM(total_price) AS total_price,
            SUM(CASE WHEN payment_status = 'completed' THEN total_price ELSE 0 END) AS completed_price
        FROM orders
        WHERE placed_on >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
          AND (DATE_FORMAT(placed_on, '%Y-%m') <> DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 12 MONTH), '%Y-%m'))
        GROUP BY order_month
        ORDER BY order_month ASC;
    ");
  $query->execute();
  $orders = $query->fetchAll(PDO::FETCH_ASSOC);

  // Function to get the last 12 months
  function getLast12Months()
  {
    $months = [];
    $current = new DateTime();
    for ($i = 0; $i < 12; $i++) {
      $months[$current->format('Y-m')] = 0; // Initialize with zero values
      $current->modify('-1 month');
    }
    return array_reverse($months); // Return in chronological order
  }

  // Initialize arrays with months
  $months = getLast12Months();
  $total_orders_per_month = array_fill_keys(array_keys($months), 0);
  $completed_orders_per_month = array_fill_keys(array_keys($months), 0);
  $total_price_per_month = array_fill_keys(array_keys($months), 0);
  $completed_price_per_month = array_fill_keys(array_keys($months), 0);

  // Populate the arrays with results
  foreach ($orders as $order) {
    $month = $order['order_month'];
    $total_orders_per_month[$month] = intval($order['total_orders']);
    $completed_orders_per_month[$month] = intval($order['completed_orders']);
    $total_price_per_month[$month] = floatval($order['total_price']);
    $completed_price_per_month[$month] = floatval($order['completed_price']);
  }

  // Return the lists as JSON
  echo json_encode([
    'months' => array_keys($months),
    'total_orders' => array_values($total_orders_per_month),
    'completed_orders' => array_values($completed_orders_per_month),
    'total_price' => array_values($total_price_per_month),
    'completed_price' => array_values($completed_price_per_month)
  ]);
} catch (PDOException $e) {
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
