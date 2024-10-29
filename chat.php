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
            <h3>Following users</h3>
            <ul id="followedUsersList">
                <?php
                $followedUsers = fetch_following_users($userId);
                foreach ($followedUsers as $user):
                ?>
                    <li>
                        <a href="#" class="followedUser" data-user-id="<?php echo $user['id']; ?>" <?php echo $index == 0 ? 'id="firstUser"' : ''; ?>>
                            <?php echo htmlspecialchars($user['username']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>

            </ul> <!-- List of users you follow will be added here -->
        </div>
        <div class="chat-container">
            
            <h2 id="chatTitle"></h2> <!-- This is where the title will show the user's name -->
            <div id="chatWindow">
            </div>
            <div class="message-input">
                <input type="hidden" id="recipientId" value="2"> <!-- The recipient user ID (set it dynamically) -->
                <input type="text" id="messageInput" placeholder="Type your message...">
                <button id="sendButton">Send</button>
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sendMessage() {
    let message = $("#messageInput").val();
    let recipientId = $("#recipientId").val();

    if (message.trim() === '') return; // Don't send empty messages

    $.post("saveMessage.php", {
        message: message,
        recipient_id: recipientId
    }, function(response) {
        $("#messageInput").val(''); // Clear input field
        loadMessages(); // Refresh chat window
    });
}

// Select the first user in the chat list when the page loads
$(document).ready(function() {
    // Automatically click on the first user in the list
    let firstUser = $("#followedUsersList .followedUser:first");
    firstUser.addClass('selected'); // Highlight the first user
    firstUser.trigger('click');
});

// When a user is clicked, update the chat title and load messages
$(".followedUser").click(function(e) {
    e.preventDefault();
    let userId = $(this).data('user-id'); // Get recipient user ID from the clicked user
    let username = $(this).text(); // Get the clicked user's username

    $("#recipientId").val(userId); // Update recipient ID input value
    $("#chatTitle").text(username); // Update the chat title with the recipient's username

    // Remove 'selected' class from any previously selected user and add it to the clicked user
    $(".followedUser").removeClass('selected');
    $(this).addClass('selected');

    loadMessages(); // Load messages for the selected user
});

// Listen for the 'click' event on the Send button
$("#sendButton").click(function() {
    sendMessage();
});

// Listen for the 'keypress' event on the input field
$("#messageInput").keypress(function(e) {
    if (e.which === 13) { // 13 is the key code for the Enter key
        e.preventDefault(); // Prevent the default behavior (new line)
        sendMessage(); // Call the sendMessage function
    }
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

        let currentUserId = <?php echo getUserID($_SESSION['username']); ?>; // Logged-in user ID

        messages.forEach(function(msg) {
            // Check if the message was sent by the current user
            let messageClass = (msg.sender_id == currentUserId) ? 'sent' : 'received';

            // Append the message with the appropriate class (sent or received)
            chatWindow.append(`
                <div class="message-box ${messageClass}">
                    <span class="sender">${msg.username}:</span>
                    <span class="message">${msg.message}</span>
                </div>
            `);
        });
    });
}

// Fetch new messages every 3 seconds
setInterval(loadMessages, 3000); // Set interval to 3000ms (3 seconds)

    </script>
        <script src="js/custom.js"></script>

</body>

</html>