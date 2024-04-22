<?php
    require_once 'baza.php';
    session_start();
    if(!isset($_SESSION['username']))
    {
      header('location: index.php');
    }
    conn();
    $username = $_SESSION['username'];
    
    if(isset($_POST["naslov"]))
    {   
        $naslov = $_POST["naslov"];
        $postdesc = $_POST["postdesc"];
        create_post($username, $naslov, $postdesc);
    ?>    
     <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script>
      $(document).ready(function(){
        $('form').submit(function(event) {
          event.preventDefault(); // Prevent the form from submitting normally
          
          var data = $($this).serialize() + '&username' + <?php // echo json_encode($username);?>;
          var params = {
            param1: <?php // echo json_encode($naslov); ?>,
            param2: <?php // echo json_encode($postdesc); ?>
          };
          data += '&' + $.param(params); // Add the parameters to the serialized form data
          
          // Send the form data using AJAX
          $.ajax({
            url: 'baza.php?function=create_post',
            type: 'POST',
            data: data,
            success: function(response) {
              // If the submission was successful, update the page content here
              console.log(response); // Debugging
              console.log("jebeno radi");
            }
          });
        });
      });
    </script>-->
    <?php 
    }

    if(isset($_POST["postID"])){
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
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <script src="https://kit.fontawesome.com/47e60dfc20.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <section id="home">
      <header>
        <div class="container">
          <div class="left" style="text-transform: uppercase;">
            <a href="profile.php" class="logo"><?php echo $username?></a>
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
    </section>
    <div class="sidebarUserinfo">
      <div class="userdata-postMaker">
        <div class="user-data userdata">
          <div class="divPfp">
          <?php display_pfp($username);?> 
         </div>
          <span class="green"><?php echo $username; ?></span>
          <!---->
        </div><button class="btn btn-open">Make Post</button>
      </div>
  </div>    
  <section class="modal hidden">
        <div class="flex">
          <div class="divPfp">
          <?php display_pfp($username);?> 
         </div><?php 
          echo "<h3>".$username."</h3>";
          ?>
          <button class="btn-close">⨉</button>
        </div>
        <form action="" method="post">
          <div class="sirina">
            <span name='<?php $username?>' style="display:none;"></span>
            <input type="text" id="text" name="naslov" placeholder="TITLE OF THE POST" maxlenght="30" required/>
            <textarea type="text" id="myTextarea" name="postdesc" maxlength="255" rows="10" required></textarea>
          </div>
          <div class="brkaraktera"><span id="lengthSpan">0</span><span>/255</span></div>
          <input type="submit" class="btn" value="Make post">
        </form>
  </section>
  <div class="overlay hidden"></div>
  <!-- Added June 12 20223-->
  <section name='posts'>
    <?php 
    $posts = get_post();
  $Post= '
  <div class="post">
    <div class="post-header">
      <div class="user-info">
        <div class="profile-picture">';

    foreach ($posts as $post){
      echo $Post;
          display_pfp($post['username']); 
          echo '
          </div>
          <h3 class="username">'. $post['username'] .'</h3>
          </div>';
          if($post['username'] == $username){

            echo '
              <form id="myForm" action="" method="post">
                <input type="hidden" name="postID" value='; echo $post['id'].'>
              </form>
                <div class="dropdown">
                <button class="dropdown-button">...</button>
                <div class="dropdown-content proportional-dropdown">
                  <a href="" id="delete_post">delete post  <i class="fa-solid fa-trash fa-beat-fade"></i></a>
                  <a href="?">two</a>
                  <a href="?">three</a>
                </div>
              </div>';
            }
            echo'</div>
              <div class="post-content">
                  <h2 class="post-title">' . $post['post_title'] . '</h2>
                  <p class="post-description">' . $post['post_desc'] . '</p>
              </div>
          </div>'; 
          
    }
    ?>

  </section>
  
  <script  src="./js/index.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/custom.js"></script>

  </body>
</html>