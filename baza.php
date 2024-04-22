<?php

// Function to establish database connection
function conn()
{
    global $conn;

    $conn = mysqli_connect("localhost", "root", "root", "social_network");
    if ($conn == false) {
        die("Greska pri konektovanju na bazu"); // Error message if connection fails
    } else {
        return true;
    }
}

// Function to disconnect from the database
function disconnect()
{
    global $conn;

    return mysqli_close($conn);
}
/*
 *
 *       SECTION DEDICATED TO USER AND PROFILE FUNCTIONS
 *
 * 
**/
// Function to register a new user
function register($username, $email, $password, $image, $tmp_name)
{
    global $conn;

    /* Preventing database injection for username, email */
    $sredjenUser = mysqli_real_escape_string($conn, $username);
    $sredjenEmail = mysqli_real_escape_string($conn, $email);
    $sredjenPass = mysqli_real_escape_string($conn, $password);

    $hashedpassword = password_hash($sredjenPass, PASSWORD_ARGON2ID); // Hashing the password using ARGON2ID algorithm

    // Moving uploaded file to 'images/' folder

    move_uploaded_file($tmp_name, "images/$image");
    $sqlUpit = "SELECT * FROM users WHERE username = '$sredjenUser'";
    $rezultat = mysqli_query($conn, $sqlUpit);
    if ($rezultat == false) {
        die('Doslo je do greske pri registraciji korisnika'); // Error message if query fails
    }

    if (mysqli_num_rows($rezultat) != 0) {
        header("location:index.php"); // Return false if user already exists
    }

    $sqlUpit = "INSERT INTO users VALUES(NULL,'$sredjenUser', '$hashedpassword','$sredjenEmail','" . $image . "')";
    $rezultat = mysqli_query($conn, $sqlUpit);
    if ($rezultat == false) {
        die('Doslo je do greske pri registraciji korisnika'); // Error message if query fails
    }
    header("location:index.php"); // Redirect to index.php after successful registration
    return true;
}
function login(string $username, string $password)
{
    global $conn;

    /* Preventing database injection */
    $sredjenUser = mysqli_real_escape_string($conn, $username);
    $sredjenPass = mysqli_real_escape_string($conn, $password);

    $sql_upit = "SELECT * FROM users WHERE username = '" . $sredjenUser . "'";
    $rezultat = mysqli_query($conn, $sql_upit);

    if (mysqli_num_rows($rezultat) == 0) {
        // If the user does not exist, display an error message
        //echo "<script>alert('user not found in database');</script>";
        header("location:index.php");
        exit;
    }

    $row = mysqli_fetch_assoc($rezultat);
    $hashedpassword = $row['password'];

    // Verify the password
    if (password_verify($sredjenPass, $hashedpassword)) {
        $_SESSION["username"] = $sredjenUser; // Store the username in session
        disconnect();
        header("location:home.php"); // Redirect to home.php after successful login
        exit; // Add exit to stop further execution
    } else {
        // Password verification failed, redirect to login page
        header("location:index.php");
        exit; // Add exit to stop further execution
    }
}
/** 
 * 
 *  function to get get user information from database
 *  this function is called only in profile.php to
 *  display user information.
 *  takes username thats stored in session so only one user can get information
 * 
 **/
function getUserInfo(string $username)
{
    global $conn;
    $sredjenUser = mysqli_real_escape_string($conn, $username);

    $sql = "SELECT * FROM users WHERE username = '$sredjenUser'";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

/**
 * 
 * @param string $username,
 * @param string $email,
 * @param string $bio
 * takes every input from form and sends it to the database where it updates the information
 */
function updateUserInfo(string $username,string $email, string $bio)
{
    global $conn;
    $sredjenUser = mysqli_real_escape_string($conn, $username);
    $sredjenEmail = mysqli_real_escape_string($conn, $email);
    $sredjenBio = mysqli_real_escape_string($conn, $bio);

    $sql = "Update users
    Set username = '" . $sredjenUser . "',email='" . $sredjenEmail . "',bio='" . $sredjenBio . "'
    where username='" . $sredjenUser . "';
    ";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        echo "<script>console.log('ne ce da radi :(')</script>";
        return false;
    }
}
/**
 * Delete user function
 * delete user from database takes 
 */
function delete_user($username)
{
    global $conn;
    $sqlUpit =  "select id from users where username='" . $username . "'";
    $userid = mysqli_query($conn, $sqlUpit);
    $sqlUpit = "delete from posts where postcreator = '" . $userid . "'";
    $result = mysqli_query($conn, $sqlUpit);
    if ($result) {
        $sql = "delete from users where username='" . $username . "'";
        $result = mysqli_query($conn, $sql);
        header("Location:logout.php");
        exit;
    } else {
        exit;
    }
}
/**
 * 
 * Count of posts made by user
 * takes $username as parameter
 */
function count_posts(string $username)
{
    global $conn;
    $sredjenUser = mysqli_real_escape_string($conn, $username);

    $sql = "select count(*) as post_count
    from posts p 
    inner join users u on p.post_creator = u.id 
    where u.username = '$sredjenUser';";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['post_count'];
}
/* Profile picture function */
// Takes the username from the session as a parameter
function display_pfp(string $username)
{

    global $conn;
    $sqlimage = "SELECT * FROM users WHERE username='$username'";
    $imageresult1 = mysqli_query($conn, $sqlimage);

    while ($row = mysqli_fetch_assoc($imageresult1)) {
        echo "<img src='./images/" . $row['image'] . "' class='pfp' alt='slika nije ucitana'>";
    }
    return "";
}

// function searchUser($search)
// {
//     global $conn;

//     // Search query
//     $sql = "SELECT * FROM users WHERE username LIKE '%$search%'";
//     $result = $conn->query($sql);

//     $users = array(); // Array to hold search results

//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             $users[] = $row; // Add user to results array
//         }
//     }

//     $conn->close();

//     return $users;
// }
/*
    *
    * 
    *   SECTION DEDACTED FOR POST FUNCTIONS
    *
    */
// Function to create a new post
function create_post($username, $param1, $param2)
{
    global $conn;
    // Sanitize the input data to prevent SQL injection attacks
    $naslov = mysqli_real_escape_string($conn, $param1);
    $postdesc = mysqli_real_escape_string($conn, $param2);
    $date = date('Y-m-d');

    $sqlQuerry = "SELECT id FROM `users` WHERE username='$username'";
    $rezultat = mysqli_query($conn, $sqlQuerry);
    if ($rezultat) {
        $row = mysqli_fetch_assoc($rezultat);
        $userid = $row['id'];
    }
    // Insert the post data into the database
    $sql = "INSERT INTO posts VALUES (NULL,'$userid', '$postdesc','$date','$naslov')";
    $rezultat = mysqli_query($conn, $sql);
    if ($rezultat) {
        return true;
        
    } else {
        return false;
    }
}
/* 
*
* function to delete post taking only post_id
*/
function delete_post($id)
{
    global $conn;
    $postID = mysqli_real_escape_string($conn, $id);

    $sql = "DELETE FROM posts WHERE id= ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $postID);
    $rezultat = mysqli_stmt_execute($stmt);

    if ($rezultat) {
        return true;
    } else {
        return false;
    }
}

/*
        Function to fetch all posts in database
        simple yet effective
        no parameters required
    */
function get_post()
{
    global $conn;
    // sql Querry to get username from table users and everithing from table posts
    $sqlQuerry = "SELECT users.username, posts.* FROM users JOIN posts ON users.id = posts.post_creator;";

    $rezultat = mysqli_query($conn, $sqlQuerry);

    if ($rezultat == false) {
        die('Greska pri dohvatanju svih korisnika' . mysqli_error($conn));
    }

    return mysqli_fetch_all($rezultat, MYSQLI_ASSOC);
}
//

conn();
if(isset($_POST['query'])) {
    $search = isset($_POST['query']) ? $_POST['query'] : ''; // Check if $_POST['query'] is set, otherwise set $search to an empty string

    if (!empty($search)) { // Check if $search is not empty
        // SQL query to search for users
        $sql = "SELECT * FROM users WHERE username LIKE '%$search%'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<form action='' method='get' name='search'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<input type='submit' name='username' value='".$row['username']."' />";
                }
                echo "</form>";
            } else {
                echo "<p>No matching users found</p>";
            }
        } else {
            echo "<p>Error executing the query: " . mysqli_error($conn) . "</p>";
        }
    }
}
