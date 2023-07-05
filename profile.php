<?php 
    require_once 'baza.php';
    session_start();
    if(!isset($_SESSION['username']))
    {
      header('location: index.php');
    }
    konekcija();
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
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  </head>
  <body>
    <section name="profle">
      <section id="navbar">
        <header>
          <div class="container">
            <div class="left" style="text-transform: uppercase;">
              <a href="#" class="logo"><?php echo $username?></a>
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
                  <li><a href="#services">Services</a></li>
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
  </section>
</body>
</html>