<?php
   require_once 'baza.php';
 
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if ($_POST["password"] == $_POST["pass"]) {

            // Database related code
           conn();
           $username = $_POST["username"];
           $password = $_POST["password"];
           $email1 = $_POST["email"];
           
           $image = $_FILES["image"]["name"];
           $tmp_name = $_FILES["image"]["tmp_name"];
           
           register($username, $email1, $password, $image, $tmp_name);
           disconnect();
       }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/register.js" defer></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body>
    <div class="login">
        <h1>Register</h1>
        <img id="img" src="./images_default/avatar.jpg">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required="required" />
            <input type="email" name="email" placeholder="Email" required="required" />
            <input type="password" name="password" id="password" placeholder="Password" required="required" />
            <input type="password" name="pass" id="confirm_password" placeholder="Reapeat Password" required="required" />
            <span id="message"></span><br>
            <!-- Upload Image part next 3 lines -->
            <input type="button" onclick="document.getElementById('getFile').click()" value="Upload your photo" id="buttonid">
            <input type="file" id="getFile" name="image" accept="image/png, image/jpg, image/jpeg" required style="display: none;">
            <span class="upload-path" style="color:#ffffff;font-size:16px;"></span>

            <button type="submit" class="btn btn-primary btn-block btn-large">Register</button>
        </form>
        <p>already a member: <a href="index.php" style="color:aliceblue; text-decoration:none;">log in now</a></p>
    </div>

    <script src="./js/register.js"></script>
</body>
</html>