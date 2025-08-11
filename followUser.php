<?php
session_start();
require_once 'baza.php';

// // Enable error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $follower_id = getUserID($_SESSION['username']); // Get current user ID
    $followed_id = isset($_POST['followed_id']) ? intval($_POST['followed_id']) : null;

    if ($follower_id && $followed_id) {
        conn(); // Establish database connection

        global $conn; // Make sure $conn is accessible here
        $stmt = $conn->prepare("INSERT INTO following (user_id, following_user_id) VALUES (?, ?)");
        
        if ($stmt === false) {
            echo json_encode(['error' => 'Prepare statement failed: ' . $conn->error]);
            disconnect();
            exit;
        }

        $stmt->bind_param("ii", $follower_id, $followed_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to follow user: ' . $stmt->error]);
        }

        $stmt->close();
        disconnect();
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
?>
