<?php
include '../components/connect.php';
// header('Content-Type: application/json'); // Set the response to JSON format
if (isset($_GET['id'])) {
  $message_id = $_GET['id'];
  $select_message = $conn->prepare("SELECT * FROM `messages` WHERE id = :id");
  $select_message->bindParam(':id', $message_id, PDO::PARAM_INT);
  $select_message->execute();

  if ($select_message->rowCount() > 0) {
    $message = $select_message->fetch(PDO::FETCH_ASSOC);

    // Return the message as a JSON object including the id
    echo json_encode([
      'id' => $message['id'],
      'name' => $message['name'],
      'email' => $message['email'],
      'number' => $message['number'],
      'message' => $message['message']
    ]);
  } else {
    echo json_encode(['error' => 'Message not found.']);
  }
} else {
  echo json_encode(['error' => 'No message ID provided.']);
}
