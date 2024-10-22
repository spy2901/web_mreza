<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    require_once 'baza.php';
    session_start();
    if (!isset($_SESSION['username'])) {
        header('location: index.php');
    }
    $username = $_SESSION['username'];
    conn();
    $userId = getUserID($_SESSION['username']); // Fetch the logged-in user's ID
    $followedUsers = fetch_following_users($userId);
?>
<!DOCTYPE html>
<html lang="en">
<!------------------------META TAGs and TITLE--------------->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Social Network</title>
<!--------------------------------     CSS    --------------------------------------------------->
<link type="text/css" rel="stylesheet" href="css/home.css">
<link type="text/css" rel="stylesheet" href="css/modal.css">
<link type="text/css" rel="stylesheet" href="css/chat.css">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
<script src="https://kit.fontawesome.com/47e60dfc20.js" crossorigin="anonymous"></script>
<!-- Include jQuery here -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <section id="home">
        <header>
            <div class="container">
                <div class="left" style="text-transform: uppercase;">
                    <a href="profile.php" class="logo"><?php echo $username ?></a>
                    <div class="hm-menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="right">
                    <nav>
                        <ul>
                            <li><a href="home.php">Home</a></li>
                            <li><a href="searchPage.php">Find Frends</a></li>
                            <li><a href="chat.php">Chat</a></li>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
    </section>
    <div class="chat-div">
        <div class="sidebar">
            <h3>Users You Follow</h3>
            <ul id="followedUsersList">
                <?php foreach ($followedUsers as $user): ?>
                    <li>
                        <a href="#" class="followedUser" data-user-id="<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul> <!-- List of users you follow will be added here -->
        </div>
        <div id="chatWindow"></div>
        <input type="hidden" id="recipientId" value="2"> <!-- The recipient user ID (set it dynamically) -->
        <input type="text" id="messageInput" placeholder="Type your message...">
        <button id="sendButton">Send</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // When a user in the sidebar is clicked, set the recipient ID
        $(".followedUser").click(function(e) {
            e.preventDefault();
            let userId = $(this).data('user-id'); // Get user ID from the clicked user
            $("#recipientId").val(userId); // Set recipient ID in hidden input field
            loadMessages(); // Load messages for the selected user
        });
        // Send message via AJAX
        $("#sendButton").click(function() {
            let message = $("#messageInput").val();
            let recipientId = $("#recipientId").val();

            $.post("saveMessage.php", {
                message: message,
                recipient_id: recipientId
            }, function(response) {
                $("#messageInput").val(''); // Clear input field
                loadMessages(); // Refresh chat window
            });
        });

        // Fetch messages via AJAX
        function loadMessages() {
            let recipientId = $("#recipientId").val();

            $.get("fetchMessages.php", {
                recipient_id: recipientId
            }, function(data) {
                let messages = JSON.parse(data);
                let chatWindow = $("#chatWindow");
                chatWindow.html(""); // Clear chat window

                messages.forEach(function(msg) {
                    chatWindow.append("<p>" + msg.message + "</p>");
                });
            });
        }

        // Fetch new messages every 3 seconds
        setInterval(loadMessages, 3000);
    </script>
</body>

</html>