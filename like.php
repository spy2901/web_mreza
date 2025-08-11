<?php
include_once 'baza.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'not_logged_in']);
    exit;
}

$username = $_SESSION['username'];
$user_id = getUserID($username);

if (isset($_POST['action'], $_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    $success = false;

    if ($action === 'like') {
        $success = like_post($user_id, $post_id);
    } elseif ($action === 'unlike') {
        $success = unlike_post($user_id, $post_id);
    }

    echo json_encode([
        'success' => (bool) $success,
        'likes' => get_like_count($post_id)
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'invalid_request']);
