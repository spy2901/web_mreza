<?php
session_start();
require_once 'baza.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    conn();  // Establish the database connection
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $sender_id = getUserID($_SESSION['username']);
    if ($sender_id === null) {
        echo json_encode(['error' => 'Invalid sender ID']);
        exit;
    }

    $recipient_id = $_GET['recipient_id'];
    if (empty($recipient_id)) {
        echo json_encode(['error' => 'Invalid recipient ID']);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY timestamp");
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
        exit;
    }

    // Bind the parameters and execute the statement
    $stmt->bind_param("iiii", $sender_id, $recipient_id, $recipient_id, $sender_id);
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
        exit;
    }

    // Fetch the results
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages); // Return messages as JSON

    // Clean up
    $stmt->close();
    disconnect();
}
?>
