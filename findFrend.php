<?php
require_once 'baza.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('location: index.php');
}
$username = $_SESSION['username'];
$sent_username = $_POST['$username']
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
                    display_pfp($sent_username);
                    disconnect(); ?>
                </div>
                <div id="userInfo">

                    <?php
                    conn();
                    $userInfo = getUserInfo($sent_username);
                    // if (!$userInfo) {
                    //   echo "User information not found!";
                    //   exit(); // Stop further execution
                    // }
                    $count_posts = count_posts($sent_username);


                    echo
                    '<form action="" method="post" id="user">
              <h2>USERNAME</h2>
              <h2>EMAIL</h2>
              <h2> BIOGRAPHY</h2>
              <input type="text" name="username" value="' . $userInfo["username"] . '" required disabled/>
              <input type="text" name="email" value="' . $userInfo["email"] . '" required disabled/> 
              <textarea name="bio" disabled>' . $userInfo['bio'] . '</textarea>
              <div></div>
              <div>User Post count:<h2><b>' . $count_posts . '</b></h2></div>
              
            </form>
              </div>';
                    ?>
                    <form action="" method="post" id="follow user">
                        <?php echo '<input type="hidden" name="username1" value="' . $userInfo["username"] . '" required/> '; ?>
                        <input type="submit" name="follow" value="Follow User">
                    </form>
                </div>
        </section>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JAVASCRIPT AND JQUERY -->
    <script src="js/custom.js"></script>
    <script src="js/search.js"></script>
</body>

</html>