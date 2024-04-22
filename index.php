<?php
    require_once 'baza.php';
    session_start();
    
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {   
        conn(); 
        $username = $_POST["username"];
        $password = $_POST["password"];
        login($username,$password);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link type="text/css" rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login">
        <h1>Login</h1>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <button type="submit" class="btn btn-primary btn-block btn-large">Login</button>
        </form>
        <p>Not member: <a href="register.php"  style="color:aliceblue; text-decoration:none;" >register now</a></p>
    </div>
</body>
</html>