<?php
session_start();
require_once 'baza.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    conn(); // Establish the database connection
    if (!$conn) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $sender_id = getUserID($_SESSION['username']);
    if ($sender_id === null) {
        echo json_encode(['error' => 'Invalid sender ID']);
        exit;
    }

    $recipient_id = $_POST['recipient_id'];
    $message = $_POST['message'];

    if (empty($recipient_id)) {
        echo json_encode(['error' => 'Recipient ID is missing']);
        exit;
    }

    if (empty($message)) {
        echo json_encode(['error' => 'Message content is missing']);
        exit;
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
        exit;
    }

    if (!$stmt->bind_param("iis", $sender_id, $recipient_id, $message)) {
        echo json_encode(['error' => 'Failed to bind parameters: ' . $stmt->error]);
        exit;
    }

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to execute query: ' . $stmt->error]);
        exit;
    }

    // Return a success response
    echo json_encode(['success' => 'Message saved successfully']);

    // Clean up
    $stmt->close();
    disconnect();
}
?>
