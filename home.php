<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'baza.php';
session_start();
if (!isset($_SESSION['username'])) {
  header('location: index.php');
  register($username, $email1, $password, $image, $tmp_name);
  exit;
}
conn();
$username = $_SESSION['username'];

if (isset($_POST["naslov"])) {
  $naslov = $_POST["naslov"];
  $postdesc = $_POST["postdesc"];
  create_post($username, $naslov, $postdesc);
}


if (isset($_POST["postID"])) {
  $postId = $_POST["postID"];
  delete_post($postId);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!------------------------META TAGs and TITLE--------------->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Social Network</title>
  <!--------------------------------     CSS    --------------------------------------------------->
  <link type="text/css" rel="stylesheet" href="css/home.css">
  <link type="text/css" rel="stylesheet" href="css/modal.css">
  <link type="text/css" rel="stylesheet" href="css/home-sidebar.css">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  <script src="https://kit.fontawesome.com/47e60dfc20.js" crossorigin="anonymous"></script>
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
  <div class="sidebarUserinfo">
    <div class="userdata-postMaker">
      <div class="user-data userdata">
        <div class="divPfp">
          <?php display_pfp($username); ?>
        </div>
        <span class="green"><?php echo $username; ?></span>
        <!---->
      </div><button class="btn btn-open">Make Post</button>
    </div>
  </div>
  <section class="modal hidden">
    <div class="flex">
      <div class="divPfp">
        <?php display_pfp($username); ?>
      </div><?php
            echo "<h3>" . $username . "</h3>";
            ?>
      <button class="btn-close">⨉</button>
    </div>
    <form action="" method="post">
      <div class="sirina">
        <span name='<?php $username ?>' style="display:none;"></span>
        <input type="text" id="text" name="naslov" placeholder="TITLE OF THE POST" maxlenght="30" required />
        <textarea type="text" id="myTextarea" name="postdesc" maxlength="255" rows="10" required></textarea>
      </div>
      <div class="brkaraktera"><span id="lengthSpan">0</span><span>/255</span></div>
      <input type="submit" class="btn" value="Make post">
    </form>
  </section>
  <div class="overlay hidden"></div>
  <section class="sidebar">
    <?php
    // Get the logged-in user ID
    $loggedInUserId = getUserID($_SESSION['username']);

    // Get 3 random users the logged-in user is not following
    $suggestedUsers = suggest_random_users_not_followed($loggedInUserId, 3);
    ?>

    <div class="sidebar">
      <h3>Suggested Users to Follow</h3>
      <ul id="suggestedUsersList">
        <?php foreach ($suggestedUsers as $user): ?>
          <li>
            <a href="#" class="suggestedUser" data-user-id="<?php echo $user['id']; ?>">
              <?php echo htmlspecialchars($user['username']); ?>
            </a>
            <button class="followButton" data-user-id="<?php echo $user['id']; ?>">Follow</button>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>


  </section>
  <!-- Added June 12 20223-->
  <section name='posts'>
    <?php
    $posts = get_post();
    ?>

    <div class="posts">
      <?php foreach ($posts as $post): ?>
        <div class="post">
          <div class="post-header">
            <div class="user-info">
              <div class="profile-picture">
                <?php display_pfp($post['username']); ?>
              </div>
              <h3 class="username"><?php echo htmlspecialchars($post['username']); ?></h3>
            </div>

            <?php if ($post['username'] == $username): ?>
              <form id="myForm" action="" method="post">
                <input type="hidden" name="postID" value="<?php echo htmlspecialchars($post['id']); ?>">
              </form>
              <div class="dropdown">
                <button class="dropdown-button">...</button>
                <div class="dropdown-content proportional-dropdown">
                  <a href="" id="delete_post">
                    delete post <i class="fa-solid fa-trash fa-beat-fade"></i>
                  </a>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <div class="post-content">
            <div class="post-header" style="background-color: transparent;">
              <h2 class="post-title"><?php echo htmlspecialchars($post['post_title']); ?></h2>
              <span class="post-date"> <?php echo htmlspecialchars($post['date']) ?></span>
            </div>
            <p class="post-description"><?php echo htmlspecialchars($post['post_desc']); ?></p>
          </div>
          <div class="post-actions">
            <i class="like-icon far fa-heart <?php echo is_post_liked($user_id, $post['id']) ? 'hidden' : ''; ?>"
              data-post-id="<?php echo $post['id']; ?>"></i>
            <i class="unlike-icon fas fa-heart <?php echo !is_post_liked($user_id, $post['id']) ? 'hidden' : ''; ?>"
              data-post-id="<?php echo $post['id']; ?>"></i>
            <span class="like-count" data-post-id="<?php echo $post['id']; ?>">
              <?php echo get_like_count($post['id']); ?> Likes
            </span>
          </div>

        </div>
      <?php endforeach; ?>
    </div>


  </section>

  <script src="./js/index.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/custom.js"></script>
  <script>
    $(".followButton").click(function(e) {
      e.preventDefault();
      let userId = $(this).data('user-id');

      $.post("followUser.php", {
        followed_id: userId
      }, function(response) {
        let data = JSON.parse(response);
        if (data.success) {

          location.reload(); // Reload the page to update the suggestions
        }
      });
    });

    document.addEventListener('DOMContentLoaded', () => {
      // Like heart click handler
      document.querySelectorAll('.like-icon').forEach(icon => {
        icon.addEventListener('click', () => {
          const postId = icon.getAttribute('data-post-id');
          handleLike(postId, 'like', icon);
        });
      });

      // Unlike heart click handler
      document.querySelectorAll('.unlike-icon').forEach(icon => {
        icon.addEventListener('click', () => {
          const postId = icon.getAttribute('data-post-id');
          handleLike(postId, 'unlike', icon);
        });
      });

      // Function to handle like/unlike actions
      function handleLike(postId, action, clickedIcon) {
        fetch('like.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=${action}&post_id=${postId}`,
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Update the like count dynamically
              const likeCountElement = document.querySelector(`.like-count[data-post-id="${postId}"]`);
              likeCountElement.textContent = `${data.likes} Likes`;

              // Toggle icon visibility
              const likeIcon = clickedIcon.closest('.post-actions').querySelector('.like-icon');
              const unlikeIcon = clickedIcon.closest('.post-actions').querySelector('.unlike-icon');

              if (action === 'like') {
                likeIcon.classList.add('hidden');
                unlikeIcon.classList.remove('hidden');
              } else {
                unlikeIcon.classList.add('hidden');
                likeIcon.classList.remove('hidden');
              }
            } else {
              console.error('Failed to update like/unlike');
            }
          })
          .catch(error => console.error('Error:', error));
      }
    });
  </script>

</body>

</html>