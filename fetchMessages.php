<?php
session_start();
require_once 'baza.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sender_id = getUserID($_SESSION['username']);
    $recipient_id = $_GET['recipient_id'];

    conn();
    $stmt = $conn->prepare("
        SELECT m.message, m.timestamp, u.username, m.sender_id 
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND m.recipient_id = ?) 
        OR (m.sender_id = ? AND m.recipient_id = ?)
        ORDER BY m.timestamp
    ");
    $stmt->bind_param("iiii", $sender_id, $recipient_id, $recipient_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row; // Include sender's ID
    }

    echo json_encode($messages);
    $stmt->close();
    disconnect();
}
?>
