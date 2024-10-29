<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'baza.php';
session_start();
if (!isset($_SESSION['username'])) {
  header('location: index.php');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["changeInfo"])) {
    $username1 = $_POST["username"];
    $email = $_POST["email"];
    $bio = $_POST["bio"];
    conn();
    updateUserInfo($username1, $email, $bio);
  }
  if (isset($_POST["username1"])) {
    $username1 = $_POST["username1"];
    conn();
    delete_user($username1);
  }
}
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];

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
    <link type="text/css" rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  </head>

  <body>
    <section name="profle">
      <section id="navbar">
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
        <section name="profile">
          <img src="" alt="">
        </section>
      </section>
      <section name="profilePage" id="profile">
        <div id="profilePicInfo">
          <div id="pf">
            <?php conn();
            display_pfp($username);
            disconnect(); ?>
          </div>
          <div id="userInfo">

            <?php
            conn();
            $userInfo = getUserInfo($username);
            // if (!$userInfo) {
            //   echo "User information not found!";
            //   exit(); // Stop further execution
            // }
            $count_posts = count_posts($username);


            echo
            '<form action="" method="post" id="user">
                            <div class="input-group">
                                <label for="username">USERNAME</label>
                                <input type="text" id="username" name="username" value="' . $userInfo["username"] . '" required/>
                            </div>
                            <div class="input-group">
                                <label for="email">EMAIL</label>
                                <input type="email" id="email" name="email" value="' . $userInfo["email"] . '" required/>
                            </div>
                            <div class="input-group">
                                <label for="bio">BIOGRAPHY</label>
                                <textarea id="bio" name="bio">' . $userInfo['bio'] . '</textarea>
                            </div>
                            <div class="input-group">
                                <label>User Post Count:</label>
                                <h2><b>' . $count_posts . '</b></h2>
                            </div>
                            <input type="submit" name="changeInfo" value="Change user information" />
                        </form>';
            ?>
            <form action="" method="post" id="deleteUser">
              <?php echo '<input type="hidden" name="username1" value="' . $userInfo["username"] . '" required/> '; ?>
              <input type="submit" name="delete" value="Delete User">
            </form>
          </div>
      </section>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script></script>
    <script src="js/custom.js"></script><!---->
  </body>

  </html><?php } else {
          $username = $_POST['username'];
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
    <link type="text/css" rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  </head>

  <body>
    <section name="profle">
      <section id="navbar">
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
                  <li><a href="#about">About</a></li>
                  <li><a href="profile.php">My Profile</a></li>
                  <li><a href="logout.php">Log out</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </header>
        <section name="profile">
          <img src="" alt="">
        </section>
      </section>
      <section name="profilePage" id="profile">
        <div id="profilePicInfo">
          <div id="pf">
            <?php conn();
            display_pfp($username);
            disconnect(); ?>
          </div>
          <div id="userInfo">

            <?php
            conn();
            $userInfo = getUserInfo($username);
            // if (!$userInfo) {
            //   echo "User information not found!";
            //   exit(); // Stop further execution
            // }
            $count_posts = count_posts($username);


            echo
            '<form action="" method="post" id="user">
              <h2>USERNAME</h2>
              <h2>EMAIL</h2>
              <h2> BIOGRAPHY</h2>
              <input type="text" name="username" value="' . $userInfo["username"] . '" required/>
              <input type="text" name="email" value="' . $userInfo["email"] . '" required/> 
              <textarea name="bio">' . $userInfo['bio'] . '</textarea>
              <div></div>
              <div>User Post count:<h2><b>' . $count_posts . '</b></h2></div>
              <input type="submit" name="changeInfo" value="Change user information" />
            </form>
              </div>';
            ?>
            <form action="" method="post" id="deleteUser">
              <?php echo '<input type="hidden" name="username1" value="' . $userInfo["username"] . '" required/> '; ?>
              <input type="submit" name="delete" value="Delete User">
            </form>
          </div>
      </section>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script></script>
    <script src="js/custom.js"></script><!---->
  </body>
<?php
        }
?>