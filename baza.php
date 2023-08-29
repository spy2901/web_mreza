<?php

    // Function to establish database connection
    function conn(){
        global $conn;

        $conn = mysqli_connect("localhost", "root", "", "social_network");
        if($conn == false){
            die("Greska pri konektovanju na bazu"); // Error message if connection fails
        }else{
            return true;
        }
    }

    // Function to disconnect from the database
    function disconnect(){
        global $conn;

        return mysqli_close($conn);
    }

    // Function to register a new user
    function register($username,$email,$password,$image,$tmp_name){
        global $conn;

        /* Preventing database injection for username, email */
        $sredjenUser = mysqli_real_escape_string($conn,$username);
        $sredjenEmail = mysqli_real_escape_string($conn,$email);
        $sredjenPass = mysqli_real_escape_string($conn,$password);

        $hashedpassword = password_hash($sredjenPass,PASSWORD_ARGON2ID); // Hashing the password using ARGON2ID algorithm

        // Moving uploaded file to 'images/' folder
        move_uploaded_file($tmp_name,"images/$image");

        $sqlUpit = "SELECT * FROM users WHERE username = '$sredjenUser'";
        $rezultat = mysqli_query($conn, $sqlUpit);
        if ($rezultat == false) {
            die('Doslo je do greske pri registraciji korisnika'); // Error message if query fails
        }

        if (mysqli_num_rows($rezultat) != 0) {
            return false; // Return false if user already exists
        }

        $sqlUpit = "INSERT INTO users VALUES(NULL,'$sredjenUser', '$hashedpassword','$sredjenEmail','$image')";
        $rezultat = mysqli_query($conn, $sqlUpit);
        if ($rezultat == false) {
            die('Doslo je do greske pri registraciji korisnika'); // Error message if query fails
        }
        header("location:index.php"); // Redirect to index.php after successful registration
        return true;
    }

    /* Login function */
    function login($username,$password){
        global $conn;

        /* Preventing database injection */
        $sredjenUser = mysqli_real_escape_string($conn,$username);
        $sredjenPass = mysqli_real_escape_string($conn,$password);

        $hashedpassword = password_hash($sredjenPass,PASSWORD_ARGON2ID); // Hashing the password using ARGON2ID algorithm

        $sql_upit = "SELECT * FROM users WHERE username = '".$sredjenUser."' and password = '".$hashedpassword."' ";

        $rezultat = mysqli_query($conn,$sql_upit);

        if (mysqli_num_rows($rezultat) == 0) {
            // If the user does not exist, display an error message
            echo "<script>alert('user not found in database');</script>";
        }

        $sql_upit = "SELECT image FROM users WHERE username = '".$sredjenUser."' and password = '".$hashedpassword."' ";
        $image1 = mysqli_query($conn,$sql_upit);
        $_SESSION['img'] = $image1; // Store the image from the database in session
        $_SESSION["username"] = $username; // Store the username in session
        header("location:home.php"); // Redirect to home.php after successful login
        return true;
    }

    /* Profile picture function */
    // Takes the username from the session as a parameter
    function display_pfp($username){
        global $conn;
        $sqlimage = "SELECT * FROM users WHERE username='$username'";
        $imageresult1 = mysqli_query($conn,$sqlimage);

        while($row = mysqli_fetch_assoc($imageresult1))
        {       
            echo "<img src='images/".$row['image']."' class='pfp' alt='slika nije ucitana'>"; 
        }
        return "";
    }

    // Function to create a new post
    function create_post($username, $param1, $param2) {
        global $conn;
        // Sanitize the input data to prevent SQL injection attacks
        $naslov = mysqli_real_escape_string($conn, $param1);
        $postdesc = mysqli_real_escape_string($conn, $param2);
        $date = date('Y-m-d');
        
        $sqlQuerry = "SELECT id FROM `users` WHERE username='$username'";
        $rezultat = mysqli_query($conn,$sqlQuerry);
        if($rezultat){
            $row = mysqli_fetch_assoc($rezultat);
            $userid = $row['id'];
        }
        // Insert the post data into the database
        $sql = "INSERT INTO posts VALUES (NULL,'$userid', '$postdesc','$date','$naslov')";
        $rezultat = mysqli_query($conn,$sql);
        if ($rezultat) {
            return true;
        } else {
            return false;
        }
    }
    /* function to delete post 
        taking only post_id
    */
    function delete_post($id) {
        global $conn;
        // Sanitize the input data to prevent SQL injection attacks
        $postID = mysqli_real_escape_string($conn, $id);
       
        $sql = "DELETE FROM posts WHERE id= '$postID'";
        $rezultat = mysqli_query($conn,$sql);
        if ($rezultat) {
            echo "<script>console.log('200')</script>";
        } else {
            return false;
        }
    }

    /*
        Function to fetch all posts in database
        simple yet effective
        no parameters required
    */
    function get_post(){
        global $conn;
        $sqlQuerry = "SELECT users.username, posts.* FROM users JOIN posts ON users.id = posts.post_creator;";
        
        $rezultat = mysqli_query($conn, $sqlQuerry);
        
        if($rezultat == false){
            die('Greska pri dohvatanju svih korisnika' . mysqli_error($conn));
        }
        
        return mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
        
    }
    
?>