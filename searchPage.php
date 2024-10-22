<?php
require_once 'baza.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('location: index.php');
}
$username = $_SESSION['username'];
if($_SERVER["REQUEST_METHOD"] =="POST"){
    if(isset($_POST["username"])){
        header('location: findFrend.php');
    }
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
    <link type="text/css" rel="stylesheet" href="css/search.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <section name="search">
            <form id="searchForm">
                <input type="text" id="search" name="search" placeholder="Search users...">
            </form>
            <div id="searchResults"></div>

        </section>
        <!-- JAVASCRIPT AND JQUERY -->
        <script src="js/custom.js"></script>
        <script src="js/search.js"></script>
</body>

</html>