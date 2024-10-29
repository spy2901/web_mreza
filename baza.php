<?php
/*
 * Section dedicated to
 * server configuration
 */
// Function to establish database connection
function conn()
{
    global $conn;

    $conn = mysqli_connect("localhost", "root", "root", "social_network");
    if (!$conn) {
        die("Greska pri konektovanju na bazu " . mysqli_connect_error()); // Error message if connection fails
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

    $sqlUpit = "SELECT * FROM users WHERE username = '$sredjenUser'";
    $rezultat = mysqli_query($conn, $sqlUpit);
    if ($rezultat == false) {
        die('Doslo je do greske pri registraciji korisnika '. mysqli_error($conn)); // Error message if query fails
    }

    if (mysqli_num_rows($rezultat) != 0) {
        header("location:index.php"); // Return false if user already exists
    }
    // Moving uploaded file to 'images/' folder

    move_uploaded_file($tmp_name, "images/$image");

    $sqlUpit = "INSERT INTO users VALUES(NULL,'$sredjenUser', '$hashedpassword','$sredjenEmail',null,'" . $image . "')";
    $rezultat = mysqli_query($conn, $sqlUpit);
    if ($rezultat == false) {
        die('Doslo je do greske pri registraciji korisnika '. mysqli_error($conn)); // Error message if query fails
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
        echo "neuspesan login";
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
function updateUserInfo(string $username, string $email, string $bio)
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

// function getUsernameById($userId) {
//     global $conn;
//     $username = null;
//     $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
    
//     $stmt->bind_result($username);
//     $stmt->fetch();
//     $stmt->close();
    
//     return $username;
// }

/**
 * 
 *  get userid function 
 * 
 *
 */ function getUserID($username)
{
    global $conn;

    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);  // Bind username as a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }

    return null;  // Return null if user not found
}
/***
 * 
 * 
 * 
 */
function suggest_random_users_not_followed($userId, $limit = 1) {
    global $conn;

    $sql = "
        SELECT u.id, u.username
        FROM users u
        WHERE u.id != ? 
        AND u.id NOT IN (SELECT following_user_id FROM following WHERE user_id = ?)
        ORDER BY RAND()
        LIMIT $limit;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);  // Only bind userId as the limit is directly included
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestedUsers = [];
    while ($row = $result->fetch_assoc()) {
        $suggestedUsers[] = $row;
    }

    $stmt->close();
    debug_to_console($suggestedUsers);  // Debug to console
    return $suggestedUsers;
}


/**
 * Summary of fetch_following_users
 * @param mixed $userId
 * @return array
 */
function fetch_following_users($userId)
{
    global $conn;

    // Check if connection is established
    if ($conn === null) {
        die("Database connection not established.");
    }

    $stmt = $conn->prepare("
        SELECT u.id, u.username 
        FROM following f 
        JOIN users u ON f.following_user_id = u.id 
        WHERE f.user_id = ?
    ");

    // Check if prepare() succeeded
    if ($stmt === false) {
        die("SQL Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("i", $userId);  // Now using user ID instead of username
    $stmt->execute();
    $result = $stmt->get_result();

    $followedUsers = [];
    while ($row = $result->fetch_assoc()) {
        $followedUsers[] = $row; // Store followed user's id and username
    }

    $stmt->close();
    return $followedUsers;
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

/**
 * 
 * @param userOne username who wants to follow
 * @param userTwo username that will be followed by userOne
 * 
 */
function followUser(string $userOne, string $userTwo)
{
    global $conn;

    // Fetch userOne's id
    $userOne = mysqli_real_escape_string($conn, $userOne);
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userOne) . "'";
    $result1 = mysqli_query($conn, $sqlUpit);

    if (mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_assoc($result1);
        $userid1 = $row1['id'];
    } else {
        // Handle case when userOne does not exist
        return "UserOne does not exist.";
    }

    // Fetch userTwo's id
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userTwo) . "'";
    $result2 = mysqli_query($conn, $sqlUpit);

    if (mysqli_num_rows($result2) > 0) {
        $row2 = mysqli_fetch_assoc($result2);
        $userid2 = $row2['id'];
    } else {
        // Handle case when userTwo does not exist
        return "UserTwo does not exist.";
    }

    // Check if the user is already following the other user
    $sqlUpit = "SELECT * FROM following WHERE user_id='$userid1' AND following_user_id='$userid2'";
    $result = mysqli_query($conn, $sqlUpit);
    if (mysqli_num_rows($result) > 0) {
        return "You are already following this user.";
    }

    // Insert new follow entry if not already following
    $sqlUpit = "INSERT INTO following (user_id, following_user_id) VALUES ('$userid1', '$userid2')";
    if (mysqli_query($conn, $sqlUpit)) {
        return "Successfully followed " . $userTwo . ".";
    } else {
        return "Error: " . mysqli_error($conn);
    }
}

function unfollowUser(string $userOne, string $userTwo)
{
    global $conn;

    // Fetch userOne's ID
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userOne) . "'";
    $result1 = mysqli_query($conn, $sqlUpit);
    if ($row1 = mysqli_fetch_assoc($result1)) {
        $userid1 = $row1['id'];
    } else {
        return "UserOne does not exist.";
    }

    // Fetch userTwo's ID
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userTwo) . "'";
    $result2 = mysqli_query($conn, $sqlUpit);
    if ($row2 = mysqli_fetch_assoc($result2)) {
        $userid2 = $row2['id'];
    } else {
        return "UserTwo does not exist.";
    }

    // Delete from the following table
    $sqlUpit = "DELETE FROM following WHERE user_id='$userid1' AND following_user_id='$userid2'";
    if (mysqli_query($conn, $sqlUpit)) {
        return "Unfollowed UserTwo successfully.";
    } else {
        return "Error: " . mysqli_error($conn);
    }
}


function checkIfFollowing(string $userOne, string $userTwo)
{
    global $conn;

    // Fetch userOne's ID
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userOne) . "'";
    $result1 = mysqli_query($conn, $sqlUpit);
    if ($row1 = mysqli_fetch_assoc($result1)) {
        $userid1 = $row1['id'];
    } else {
        return false; // UserOne not found
    }

    // Fetch userTwo's ID
    $sqlUpit = "SELECT id FROM users WHERE username='" . mysqli_real_escape_string($conn, $userTwo) . "'";
    $result2 = mysqli_query($conn, $sqlUpit);
    if ($row2 = mysqli_fetch_assoc($result2)) {
        $userid2 = $row2['id'];
    } else {
        return false; // UserTwo not found
    }

    // Check if userOne is following userTwo
    $sqlUpit = "SELECT * FROM following WHERE user_id='$userid1' AND following_user_id='$userid2'";
    $result = mysqli_query($conn, $sqlUpit);

    return mysqli_num_rows($result) > 0; // Returns true if the user is already following
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
if (isset($_POST['query'])) {
    $search = isset($_POST['query']) ? $_POST['query'] : ''; // Check if $_POST['query'] is set, otherwise set $search to an empty string

    if (!empty($search)) { // Check if $search is not empty
        // SQL query to search for users
        $sql = "SELECT * FROM users WHERE username LIKE '%$search%'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<form action='findFrend.php' method='post' name='search' class='result'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='resultItem'>";
                    echo "<input type='submit' name='username' value='" . htmlspecialchars($row['username']) . "' />";
                    echo "</div>";
                    echo "<br/>";
                }
                echo "</form>";
            } else {
                echo "<p class='noUser'>No matching users found</p>";
            }
        } else {
            echo "<p>Error executing the query: " . mysqli_error($conn) . "</p>";
        }
    }
}



/**
 * 
 * 
 * SECTION FOR UTILY FUNCTIONS
 * 
 * 
 */

 function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}